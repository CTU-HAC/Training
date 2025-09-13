import { createContext, useEffect, useState } from "react";
import axios from 'axios'
export const StoreContext = createContext(null)

const StoreContextProvider = (props) => {
    
    const [cartItems, setCartItems] = useState({});
    const url = 'http://localhost:4000'
    const [token, setToken] = useState('');
    const [food_list, setFoodList] = useState([])
    const [userInfo, setUserInfo] = useState(null);

    const addToCart = async (itemId) => {
        setCartItems((prev)=>{
            const current = Number(prev?.[itemId] || 0);
            return { ...(prev || {}), [itemId]: current + 1 };
        })
        if (token) {
            try { await axios.post(url+'/api/cart/add', {itemId}, {headers:{token}}) } catch {}
        }
    }

    const removeFromCart = async (itemId) => {
        setCartItems((prev) => {
            const current = Number(prev?.[itemId] || 0);
            const next = Math.max(0, current - 1);
            return { ...(prev || {}), [itemId]: next };
        })
        if (token) {
            try { await axios.post(url+'/api/cart/remove', {itemId}, {headers:{token}}) } catch {}
        }
    }

    const getTotalCartAmount = () => {
        let totalAmount = 0;
        if (!cartItems) return 0;
        for (const item in cartItems) {
            const qty = Number(cartItems[item] || 0);
            if (qty > 0) {
                let itemInfo = food_list.find((product) => product._id === item)
                if (itemInfo) totalAmount += Number(itemInfo.price || 0) * qty
            }
        }
        return totalAmount;
    }

    const fetchFoodList = async () => {
        const response = await axios.get(url + '/api/food/list');
        setFoodList(response.data.data)
    }

    const loadCartData = async(token) => {
        const response = await axios.post(url + '/api/cart/get', {}, { headers: { token } })
        setCartItems(response.data.cartData)
    }

    const getUserInfo = async () => {
        if (!token) return null;
        try {
            const response = await axios.get(url + '/api/user/myInfo', { headers: { token } });
            const user = response.data.user;
            setUserInfo(user);
            return user;
        } catch (error) {
            console.error("Error fetching user info:", error);
            return null;
        }
    }

    const redeemCoupon = async (code) => {
        if (!token) throw new Error('Not authenticated');
        const res = await axios.post(url + '/api/user/redeem', { code }, { headers: { token } });
        if (res.data.success) {
            if (res.data.user) setUserInfo(res.data.user);
        }
        return res.data;
    }

    useEffect(() => {
        const loadData = async () => {
            await fetchFoodList();
            const stored = localStorage.getItem("token");
            if (stored) {
                setToken(stored)
                await loadCartData(stored);
            }
        }
        loadData();
    },[])

    const contextValue = {
        food_list,
        cartItems,
        setCartItems,
        addToCart,
        removeFromCart,
        getTotalCartAmount,
        url,
    token, setToken,
        loadCartData,
    getUserInfo,
    userInfo,
    redeemCoupon,
    }


    return (
        <StoreContext.Provider value={contextValue}>
            {props.children}
        </StoreContext.Provider> 
    )
}

export default StoreContextProvider