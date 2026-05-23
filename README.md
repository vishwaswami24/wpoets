# Wpoets

<img width="1909" height="911" alt="image" src="https://github.com/user-attachments/assets/37fc2406-3f9c-4902-9ee4-766856bd1e62" />
<img width="527" height="817" alt="image" src="https://github.com/user-attachments/assets/40089d6a-ff2e-4ae7-baa3-336e54b90c7a" />


[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)](#)
[![jQuery/Vanilla JS](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat&logo=javascript&logoColor=black)](#)
[![CSS](https://img.shields.io/badge/CSS3-Modern-1572B6?style=flat&logo=css3&logoColor=white)](#)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)](#)
[![Status](https://img.shields.io/badge/Status-Active-success?style=flat)](#)

## Overview
Wpoets is a small web application (PHP-based) that serves interactive, responsive UI—currently including a slider component styled in `assets/`.

## Tech Stack
- **Backend:** PHP
- **Frontend:** HTML/CSS/JavaScript
- **Styling:** CSS (with responsive/mobile slider styles in `assets/slider-both.css`)
- **Assets:** `assets/` and `files/images/`

## Features
- Responsive slider UI (desktop + mobile)
- Mobile accordion-style content section

## Local Development / Setup
1. Ensure you have a PHP runtime installed.
2. Point a local web server at the repository root (or open with your preferred stack).
3. Configure application settings in `config.php`.

## Project Structure (high-level)
- `index.php` – entry point
- `config.php` – configuration
- `api/` – CRUD-like endpoints (`create`, `list`, `update`, `delete`)
- `assets/` – styles/scripts for the UI
- `files/` – uploaded/static content and images

## Testing
No automated tests are currently defined. Verify UI changes by running the app locally and checking desktop + mobile layouts.

## Contributing
Contributions are welcome. Keep changes focused (CSS/JS/PHP separated by responsibility) and update documentation when adding new dependencies.

## License
MIT License. See [LICENSE](LICENSE).

