// Seed script to initialize MongoDB with consistent users, foods, and orders
// This module is safe to import from the running server. It does NOT call process.exit.
import 'dotenv/config'
import mongoose from 'mongoose'
import bcrypt from 'bcrypt'
import connection from './db.js'
import userModel from '../models/userModel.js'
import foodModel from '../models/foodModel.js'
import orderModel from '../models/orderModel.js'

const sleep = (ms) => new Promise((res) => setTimeout(res, ms))

async function main() {
	// Retry DB connection a few times in case Mongo isn't ready yet
	const maxRetries = 10
	for (let i = 0; i < maxRetries; i++) {
		try {
			await connection()
			break
		} catch (e) {
			if (i === maxRetries - 1) throw e
			await sleep(1000)
		}
	}

	try {
		// Ensure we're operating against the configured DB (already set in mongoose.connect options)
		const dbName = process.env.DB_NAME || 'food_delivery'
		if (mongoose.connection.name !== dbName) {
			// Not fatal; log for visibility
			console.warn(`[seed] Connected to DB "${mongoose.connection.name}", expected "${dbName}"`)
		}

		const userCount = await userModel.countDocuments()
		const foodCount = await foodModel.countDocuments()
		const orderCount = await orderModel.countDocuments()

		if (userCount > 0 || foodCount > 0 || orderCount > 0) {
			console.log('[seed] Data already present, skipping seeding')
			return { skipped: true }
		}

		// Create users (passwords are bcrypt hashed)
		const adminPassword = await bcrypt.hash('Admin@1234', 10)
		const userPassword = await bcrypt.hash('User@1234', 10)

		const [admin, user] = await userModel.insertMany([
			{ name: 'Admin', email: 'admin@example.com', password: adminPassword, cash: 0 },
			{ name: 'Alice', email: 'alice@example.com', password: userPassword, cash: 0 },
		])

			// Create foods aligned with frontend categories (menu_list) and actual upload filenames
			const categories = [
				'Salad', 'Rolls', 'Deserts', 'Sandwich', 'Cake', 'Pure Veg', 'Pasta', 'Noodles'
			]

			// Map categories to the uploaded image filenames you added
			const imageMap = {
				'Salad': ['salad_1.png', 'salad_2.png'],
				'Rolls': ['roll_1.png', 'roll_2.png'],
				'Deserts': ['desert_1.png', 'desert_2.jfif'],
				'Sandwich': ['sandwich_1.png', 'sandwich_2.png'],
				'Cake': ['cake_1.png', 'cake_2.png'],
				'Pure Veg': ['pure_veg_1.png', 'pure_veg_2.png'],
				'Pasta': ['pasta_1.png', 'pasta_2.png'],
				'Noodles': ['noodles_1.png', 'noodles_2.png']
			}

			const seedFoods = []
			categories.forEach((cat) => {
				const imgs = imageMap[cat] || []
				seedFoods.push(
					{
						name: `${cat} Special 1`,
						description: `Tasty ${cat.toLowerCase()} option with fresh ingredients`,
						price: 10 + Math.floor(Math.random() * 10),
						image: imgs[0] || '',
						category: cat,
					},
					{
						name: `${cat} Special 2`,
						description: `Signature ${cat.toLowerCase()} loved by our customers`,
						price: 12 + Math.floor(Math.random() * 10),
						image: imgs[1] || '',
						category: cat,
					}
				)
			})

			const foods = await foodModel.insertMany(seedFoods)

		// Helper to pick foods and build items with quantities
		const pickItems = (list) => {
			return list.map((f, idx) => ({
				_id: f._id, // helpful in frontend, though not strictly used
				name: f.name,
				price: f.price,
				quantity: idx % 2 === 0 ? 1 : 2,
			}))
		}

			// Create example orders using a spread across categories
			const userItems = pickItems(foods.filter(f => f.category === 'Salad').slice(0, 1)
				.concat(foods.filter(f => f.category === 'Pasta').slice(0, 1)))
			const adminItems = pickItems(foods.filter(f => f.category === 'Noodles').slice(0, 1)
				.concat(foods.filter(f => f.category === 'Sandwich').slice(0, 1)))

		const sum = (items) => items.reduce((acc, it) => acc + it.price * it.quantity, 0)

		await orderModel.insertMany([
			{
				userId: user._id.toString(),
				items: userItems,
				amount: sum(userItems),
				address: {
					firstName: 'Alice', lastName: 'Liddell', email: 'alice@example.com',
					street: '42 Wonderland Ave', city: 'Wonder', state: 'WL', zipcode: '42424', country: 'Dream', phone: '+10000000001'
				},
				status: 'Paid',
				payment: true,
			},
			{
				userId: admin._id.toString(),
				items: adminItems,
				amount: sum(adminItems),
				address: {
					firstName: 'Admin', lastName: 'User', email: 'admin@example.com',
					street: '1 Admin Plaza', city: 'Root', state: 'RU', zipcode: '00000', country: 'Core', phone: '+10000000000'
				},
				status: 'Food Processing',
				payment: false,
			}
		])

		console.log('[seed] Seed data inserted successfully')
		return { skipped: false }
	} catch (err) {
		console.error('[seed] Error seeding database:', err)
		throw err
	}
}

export default main;

