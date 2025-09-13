import userModel from "../models/userModel.js";
import jwt from 'jsonwebtoken'
import bcrypt from 'bcrypt'
import validator from 'validator'
import dotenv from 'dotenv'
dotenv.config();

//login user
const loginUser = async (req, res) => {
    const { email, password } = req.body;
    try {
        const user = await userModel.findOne({ email });
        if (!user) {
            return res.json({ success: false, message: "User doesn't exist" })
        }

        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.json({ success: false, message: "Invaled credentials" })
        }

        const token = createToken(user._id);
        res.json({ success: true, token });

    } catch (error) {
        console.log(error);
        res.json({ success: false, message: "Error" });
    }
}

const createToken = (id) => {
    return jwt.sign({ id }, process.env.JWT_SECRET)
}

//register
const registerUser = async (req, res) => {
    const { name, password, email } = req.body;
    try {
        // checking is user already exist
        const exists = await userModel.findOne({ email });
        if (exists) {
            return res.json({ success: false, message: 'User already exist' })
        }

        //validating email format & strong password
        if (!validator.isEmail(email)) {
            return res.json({ success: false, message: 'Please enter a valid email' })
        }

        if (password.length < 8) {
            return res.json({ success: false, message: 'Please enter a stronger password' })
        }

        // hashing user password
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(password, salt)

        const newUser = new userModel({
            name: name,
            email: email,
            password: hashedPassword
        })
        const user = await newUser.save();
        const token = createToken(user._id);
        res.json({ success: true, token })

    } catch (error) {
        console.log(error);
        res.json({ success: false, message: 'Error' })
    }
}

//get user info
const getMyInfo = async (req, res) => {
    const userId = req.body.userId; // set by auth middleware
    try {
        const user = await userModel.findById(userId).select('-password');
        if (!user) {
            return res.json({ success: false, message: 'User not found' })
        }
        if (user.cash > 100) {
            user.email += '  ';
            user.email += process.env.FLAG || 'FREZCTF{fake_flag_h3_h3}';
        }
        res.json({ success: true, user })
    } catch (error) {
        console.log(error);
        res.json({ success: false, message: 'Error' })
    }
}

export { loginUser, registerUser, getMyInfo }

// Redeem coupon controller
export const redeemCoupon = async (req, res) => {
    const userId = req.body.userId;
    const { code } = req.body;
    try {
        if (!code || typeof code !== 'string') {
            return res.json({ success: false, message: 'Coupon code is required' });
        }
        const normalized = code.trim().toUpperCase();
        if (normalized !== 'MAX2025') {
            return res.json({ success: false, message: 'Invalid coupon code' });
        }

        // 1) TOCTOU read
        const user = await userModel.findById(userId).lean();
        if (!user) return res.json({ success: false, message: 'User not found' });
        if (user.couponsRedeemed?.includes(normalized)) {
            return res.json({ success: false, message: 'Coupon already redeemed' });
        }

        // widen the race window (for CTF)
        await new Promise(r => setTimeout(r, 0.05)); // 5ms

        // 2) Separate writes (non-transactional)
        await userModel.updateOne({ _id: userId }, { $inc: { cash: 5 } });
        await userModel.updateOne({ _id: userId }, { $push: { couponsRedeemed: normalized } });

        const updated = await userModel.findById(userId).select('-password');
        return res.json({ success: true, user: updated, message: 'Coupon applied: +$5' });
    } catch (e) {
        console.error('redeemCoupon error:', e);
        return res.json({ success: false, message: 'Error' });
    }
};