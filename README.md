# Tenant Upload Portal

A Laravel project for uploading CSV files of tenant data, parsing them, and returning structured JSON responses.

---

## Prerequisites

- **Docker** and **Docker Compose**  
- **Make**  (optional, for convenience)

This project uses Laravel Sail, so it can run on macOS, Linux, or Windows (via WSL).
*If using WSL, make sure Docker is running with WSL backend enabled:*
https://learn.microsoft.com/en-us/windows/wsl/tutorials/wsl-containers

---

## Setup

1. **Clone the repository**

```bash
git clone https://github.com/EMChadwick/StreetGroupTechnicalChallenge.git
cd StreetGroupTechnicalChallenge
```
2. **Set up the environment**
```bash
composer install

cp .env.example .env

```
3. **Run the project**
```bash
./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan migrate
```
*(or use Make up if using Make)*

4. **Run tests**
Tests can be run with the 'Make test' wrapper, or with
```bash
./vendor/bin/sail phpunit
```