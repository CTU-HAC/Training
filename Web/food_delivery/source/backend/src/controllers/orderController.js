import orderModel from "../models/orderModel.js";
import userModel from '../models/userModel.js';

// placing user order (wallet cash)
const placeOrder = async (req, res) => {
    try {
        const { userId, items = [], address = {} } = req.body;
        const deliveryFee = 2;

        // Compute amount server-side to prevent tampering
        const itemsTotal = Array.isArray(items)
            ? items.reduce((sum, it) => sum + Number(it.price || 0) * Number(it.quantity || 0), 0)
            : 0;
        const amount = itemsTotal + deliveryFee;

        // Check user cash
        const user = await userModel.findById(userId).select('cash');
        if (!user) return res.json({ success: false, message: 'User not found' });
        if (Number(user.cash || 0) < amount) {
            return res.json({ success: false, message: 'Insufficient cash balance' });
        }

        // Create paid order
        const newOrder = new orderModel({
            userId: userId,
            items: items,
            amount: amount,
            address: address,
            status: 'Paid',
            payment: true,
        });
        await newOrder.save();

        // Deduct cash and clear cart
        await userModel.findByIdAndUpdate(userId, { $inc: { cash: -amount }, cartData: {} });

        return res.json({ success: true, orderId: newOrder._id, message: 'Order placed using cash' });
    } catch (error) {
        console.log(error);
        res.json({ success: false, message: 'Error' });
    }
}

// verify endpoint is no longer used with wallet flow, keep for compatibility
const verifyOrder = async (req, res) => {
    return res.json({ success: false, message: 'Not applicable' });
}

//user orders for frontend
const userOrders = async (req, res) => {
    try {
        const orders = await orderModel.find({ userId: req.body.userId })
        res.json({ success: true, data: orders });
    } catch (error) {
        console.log(error);
        res.json({success: false, message:"Error"})
    }
}

//Listing orders for admin panel
const listOrders = async (req, res) => {
    try {
        const orders = await orderModel.find({});
        res.json({ success: true, data: orders });
    } catch (error) {
        console.log(error);
        res.json({success: false, message:'Error'})
    }
}

//api for update order status
const updateStatus = async (req, res) => {
    try {
        await orderModel.findByIdAndUpdate(req.body.orderId, { status: req.body.status });
        res.json({ success: true, message: 'Status Updated' });
    } catch (error) {
        console.log(error);
        res.json({ success: false, message: 'Error' });
    }
}

export { placeOrder, verifyOrder, userOrders, listOrders, updateStatus }