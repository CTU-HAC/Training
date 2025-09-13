import React, { useContext, useEffect, useState } from 'react'
import { StoreContext } from '../../content/StoreContext'

const Profile = () => {
  const { getUserInfo, token, redeemCoupon } = useContext(StoreContext);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [profile, setProfile] = useState(null);
  const [code, setCode] = useState('');
  const [msg, setMsg] = useState('');

  useEffect(() => {
    const run = async () => {
      if (!token) { setLoading(false); return; }
      try {
        const data = await getUserInfo();
        setProfile(data);
      } catch (e) {
        setError('Failed to load profile');
      } finally {
        setLoading(false);
      }
    };
    run();
  }, [token]);

  if (!token) return <div>Please login to view your profile.</div>;
  if (loading) return <div>Loading profile...</div>;
  if (error) return <div>{error}</div>;
  if (!profile) return <div>No profile data</div>;

  return (
    <div className='profile-page' style={{minHeight: '80vh'}}>
      <h2>My Profile</h2>
      <div className='profile-field'>
        <strong>Email:</strong> <span>{profile.email}</span>
      </div>
      <div className='profile-field'>
        <strong>Cash:</strong> <span>${Number(profile.cash || 0).toFixed(2)}</span>
      </div>
      <div className='coupon-redeem' style={{marginTop: '16px'}}>
        <h3>Redeem Coupon</h3>
        <div style={{ display: 'flex', gap: 8 }}>
          <input
            type='text'
            placeholder='Enter coupon code'
            value={code}
            onChange={(e) => setCode(e.target.value)}
          />
          <button onClick={async () => {
            setMsg('');
            const res = await redeemCoupon(code);
            if (res.success) {
              setMsg(res.message || 'Coupon applied');
              const data = await getUserInfo();
              setProfile(data);
            } else {
              setMsg(res.message || 'Failed to apply coupon');
            }
          }}>Apply</button>
        </div>
        {msg && <p>{msg}</p>}
      </div>
    </div>
  )
}

export default Profile