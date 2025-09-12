import React from 'react'
import './CouponBanner.css'
import { Link } from 'react-router-dom'

const CouponBanner = () => {
  return (
    <div className='coupon-banner'>
      <div className='coupon-banner-content'>
        <div className='coupon-text'>
          <span className='badge'>New</span>
          <h3>Use code <strong>MAX2025</strong> to get <strong>$5</strong>!</h3>
          <p>One-time redeem per user. Limited time offer.</p>
          <div className='actions'>
            <Link to='/profile' className='cta'>Redeem now</Link>
          </div>
        </div>
      </div>
    </div>
  )
}

export default CouponBanner
