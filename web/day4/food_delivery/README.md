# Basic Race Conditions CTF Challenge

![Challenge Type](https://img.shields.io/badge/Type-Web%20Exploitation-red)
![Difficulty](https://img.shields.io/badge/Difficulty-Beginner-green)
![Points](https://img.shields.io/badge/Points-100-blue)

## Challenge Information

- Name: Food Delivery 1
- Category: Web Exploitation
- Difficulty: Beginner
- Description: A beginner-friendly race condition/logic flaw challenge in a Node.js food-delivery app. Learn to exploit concurrent requests to bypass a check and capture the flag.
- Flag Format: `FREZCTF{...}`

## Objective

Training race condition attack skill.

> Hint: You should have more than 100 dollars to get the flag...


## Quick Start

### Prerequisites
- Docker

### Setup Instructions

1. Clone/Download the challenge files
2. Navigate to the challenge directory (the one containing `docker-compose.yml`)
3. Start the application:
   ```powershell
   docker-compose up -d
   ```
4. Access the application:
   - Frontend: http://localhost:5173
   - API: http://localhost:4000
5. Explore the functionality and exploit the race condition to get the flag!

### Stop the Challenge
```powershell
docker-compose down
```

### Clean Up (Remove all data)
```powershell
docker-compose down -v
```

## Technical Details

### Architecture
- Backend: Node.js (Express)
- Database: MongoDB
- Frontend: React (Vite)

### Services
- mongo: MongoDB database
- backend: Express API (port 4000)
- frontend: User-facing React app (port 5173)
- admin: Admin React app (port 5174)

### Data & Media
- Seed data includes two users, multiple food categories and items (with images).
- Images are served from the backend at: `http://localhost:4000/images/<filename>`

## Write-up
Update soon

---

Author: Frez  
Created: 2025  
License: Educational Use Only