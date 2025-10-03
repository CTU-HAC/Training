from Crypto.Cipher import ChaCha20
from secret import Flag
import os

def encrypt(plaintext, key, nonce):
    cipher = ChaCha20.new(key=key, nonce=nonce)
    ciphertext = cipher.encrypt(plaintext)
    return ciphertext

def write_data(data):
    with open("out.txt", "w") as f:
        f.write(data)
    
if __name__ == "__main__":
    plaintext = b"Recycling "
    plaintext += b"is a way "
    plaintext += b"to save spaces? "
    plaintext += b"Did i learn it right?"
    
    key, iv = os.urandom(32), os.urandom(12)
    
    ciphertext = encrypt(plaintext, key, iv)
    encrypted_flag = encrypt(Flag, key, iv)
    
    data = iv.hex() + "\n" + ciphertext.hex() + "\n" + encrypted_flag.hex()
    write_data(data)
