from cryptography.hazmat.primitives.ciphers.aead import ChaCha20Poly1305
import hashlib, os

#flag và passphrase vô tình bị xoá :<


key = hashlib.sha256(passphrase).digest()

aead = ChaCha20Poly1305(key)
nonce = os.urandom(12)  # 12 bytes recommended
aad = b""

ciphertext = aead.encrypt(nonce, flag, aad)

# Ghi ra file
out_path = "cipher_hex.txt"
with open(out_path, "w") as f:
    f.write((nonce + ciphertext).hex())  # nonce (12) || ciphertext+tag in hex

print(f"Cipher written to {out_path}")
