import express from 'express'
import { loginUser, registerUser, getMyInfo, redeemCoupon } from '../controllers/userController.js'
import authMiddleware from '../middleware/auth.js'

const userRouter = express.Router()

userRouter.post("/register", registerUser)
userRouter.post('/login', loginUser)
userRouter.get('/myInfo', authMiddleware, getMyInfo);
userRouter.post('/redeem', authMiddleware, redeemCoupon);

export default userRouter;