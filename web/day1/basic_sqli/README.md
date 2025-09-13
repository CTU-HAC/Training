# Basic SQL Injection CTF Challenge

![Challenge Type](https://img.shields.io/badge/Type-Web%20Exploitation-red)
![Difficulty](https://img.shields.io/badge/Difficulty-Beginner-green)
![Points](https://img.shields.io/badge/Points-100-blue)

## Challenge Information

- **Name**: Basic SQLi 1
- **Category**: Web Exploitation
- **Difficulty**: Beginner
- **Description**: A classic SQL injection vulnerability challenge. Perfect for learning the basics of web security exploitation.
- **Flag Format**: `FREZCTF{...}`

## Objective

Your mission is to exploit a SQL injection vulnerability to login as an administrator and capture the flag.

> **Hint**: You need to login as an admin to get the flag. The admin email is `admin@example.com`.

## Quick Start

### Prerequisites
- Docker

### Setup Instructions

1. **Clone/Download** the challenge files
2. **Navigate** to the project directory:
   ```bash
   cd source
   ```
3. **Start** the application:
   ```bash
   docker-compose up -d
   ```
4. **Access** the application:
   - Open your browser and go to `http://localhost:5656`
5. **Exploit** the vulnerability to get the flag!

### Stop the Challenge
```bash
docker-compose down
```

### Clean Up (Remove all data)
```bash
docker-compose down -v
```

## Technical Details

### Architecture
- **Web Server**: PHP 8.1 with Apache
- **Database**: MySQL 8.0
- **Port**: 5656 (HTTP)

### Services
- `web`: PHP application container
- `db`: MySQL database container

## Write-up
### Update soon

---

**Author**: Frez  
**Created**: 2025  
**License**: Educational Use Only