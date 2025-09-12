import express from 'express'
import cors from 'cors'
import connection from "./src/config/db.js"
import foodRouter from './src/routes/foodRoute.js';
import userRouter from './src/routes/userRoute.js';
import cartRouter from './src/routes/cartRoute.js';
import orderRouter from './src/routes/orderRoute.js';
import 'dotenv/config'
import initData from './src/config/init.js';


//app config
const app = express();
const port = 4000;

//middleware
app.use(express.json())
app.use(cors())

//db connection
await connection();

// optional seed on start (controlled via env var SEED_ON_START=true)
if (process.env.SEED_ON_START === 'true') {
    try {
        await initData();
    } catch (e) {
        console.error('Seeding failed:', e.message || e)
    }
}

// api endpoints
app.use("/api/food", foodRouter)
// Serve uploaded images; prefer absolute path to avoid cwd issues
app.use("/images", express.static('uploads'))
app.use('/api/user', userRouter)
app.use('/api/cart', cartRouter)
app.use('/api/order', orderRouter);

app.get('/', (req, res) => {
    res.send('API working')
})

app.listen(port, () => {
    console.log(`Server Started on http://localhost:${port}`)
})