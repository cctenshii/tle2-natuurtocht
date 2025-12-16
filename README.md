# 🌿 TLE2 Natuurtocht — Natuur Dex

<p align="center">
  <img src="https://img.shields.io/badge/Framework-Laravel-ff2d20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Frontend-Alpine.js-8bc0d0?style=for-the-badge&logo=alpinedotjs&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Styling-Tailwind_CSS-38bdf8?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/Database-MySQL-4479a1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---
<details>
    
<summary>## 📖 Introduction</summary>
Welcome to the **Natuurtocht** Application. This project is an interactive web application that allows users to head into nature to discover specific plants, trees, and fungi. By taking photos of found items, users fill their digital **"Natuur Dex"**.
</details>

<details>
    
<summary> ## 🚀 Core Functionalities </summary>
* **🔍 Nature Dex: An overview of all collectable nature items, grouped by category (Trees, Plants, Flowers, Fungi).

* **🍂 Seasonal Filters: The Dex automatically adapts to the current season or can be manually filtered (Spring, Summer, Autumn, Winter).

* **📸 Camera Integration: In-browser camera functionality to directly take and upload photos.

* **📊 Progression System: Users can immediately see what percentage of items they have found in the current area/season.

* **🧙‍♂️ Wizard of Oz Validation: A simulated AI validation system to test photo uploads (see section **Validation Simulation**).

* **💡 Rich Data: Each item contains facts, location information, and quiz questions.
</details>

<details>
<summary>## 🛠️ Tech Stack</summary>

| Category | Technology | Comments |
| :--- | :--- | :--- |
| **Framework** | Laravel (PHP) | Backend logic and routing. |
| **Frontend** | Blade Templates | View rendering. |
| **Styling** | Tailwind CSS | Utility-first CSS framework. |
| **Interactiviteit** | Alpine.js | lightweight JS for camera, accordions and modals. |
| **Database** | MySQL / SQLite | Datastorage. |
</details>

<details>
<summary> ## ⚙️ Installation and run locally </summary>

Follow these steps to run locally


### 1. Repository clone and navigate
```bash
git clone https://github.com/ThijsVanLoo1/tle2-natuurtocht.git
cd tle2-natuurtocht
```


2. Install dependencies

```Bash
composer install
npm install
```


3. Environment Setup
Copy the .env.example file and generate the application key. Ensure your database details are correctly configured in the .env file.

```Bash
cp .env.example .env
php artisan key:generate
```

4. Database Migrations & Seeding (Crucial)
This is a crucial step. The ManualCardSeeder fills the database with all nature cards (Nettle, Oak, etc.) including rich JSON data.

```Bash
php artisan migrate:fresh --seed
```

5. Start the Server
Start the frontend asset watcher and the Laravel development server.

```Bash
# Terminal 1: Frontend assets compiler
npm run dev

# Terminal 2: Laravel server
php artisan serve
```
</details>

<details>
<summary>📸 Validation Simulation (Wizard of Oz) </summary>

For User Story 19 ("As a user I want to know if the photo is correct") a Wizard of Oz method has been implemented. Since there is no real AI image recognition yet, we simulate this process.

❌ Incorrect Photo Click the "Use photo" button with the mouse. wizard_correct = 0 Error message: "Unfortunately, the photo is not recognized as a [Card name]..." ✅ Correct Photo Press the ENTER key on the keyboard (while the preview is visible). wizard_correct = 1 Approved, uploaded, card added to collection.

The PhotoController checks the wizard_correct value and returns a 422 error if it is 0 (Incorrect).
</details>

<details>

<summary>📂 Project Structure (Key Files)</summary>
Some important files in the codebase: app/Models/Card.php: The main model. Uses a JSON column (properties) to store flexible data (facts, quiz questions, characteristics). app/Http/Controllers/NatuurDexController.php: Manages the logic for the dashboard, including seasonal filtering and calculating progress percentages. app/Http/Controllers/PhotoController.php: Processes the upload, performs the "Wizard of Oz" validation, and links the card to the user. resources/views/cards/show.blade.php: The detail page. Contains the Alpine.js logic (x-data="camera(...)") for controlling the webcam and capturing the Enter key. 🧪 Database Seeding Example The ManualCardSeeder fills the cards table with rich data. The structure of the data in the properties JSON column looks approximately like this:

```JSON


{
    "rijk": "Plant",
    "seizoen": "Lente, Zomer",
    "feitje": "Wist je dat...",
    "kenmerken": "Groene bladeren...",
    "locatie_text": "Bosranden"
}

```
<details>

<summary>📊 Database Schema (ERD)</summary>

The following diagram visualizes the relationships between the database tables, such as users, nature cards, and collection progress.

```
erDiagram
    users ||--o{ user_cards : "has"
    users ||--o{ point_transactions : "earns"
    users ||--o{ friends : "has"
    cards ||--o{ user_cards : "collected as"
    cards ||--o{ quiz : "has"
    cards ||--o{ card_location : "found in"
    cards ||--o{ card_season : "available in"
    categories ||--o{ cards : "classifies"
    locations ||--o{ card_location : "contains"
    seasons ||--o{ card_season : "contains"

    users {
        bigint id PK
        varchar username
        varchar email
        varchar password
        varchar picture_url
        boolean admin
        bigint point_balance
    }

    cards {
        bigint id PK
        varchar name
        json properties
        text description
        bigint category_id FK
        text images
    }

    user_cards {
        bigint user_id FK
        bigint card_id FK
        date acquired_at
        varchar image_url
        boolean is_shiny
    }

    quiz {
        bigint id PK
        json answers
        text question_text
        text explanation
        bigint card_id FK
    }

    point_transactions {
        bigint id PK
        bigint user_id FK
        bigint card_id FK
        varchar action
        int points
        json meta
    }

    categories {
        bigint id PK
        varchar name
    }

    friends {
        bigint id PK
        bigint user_id FK
        bigint friend_id FK
    }
```

Table Descriptions
users: Manages user profiles, credentials, and point balances.

cards: The core table containing all nature items (Natuur Dex entries).

user_cards: A pivot table tracking which user has collected which card, including their custom image_url and "shiny" status.

point_transactions: A log of points earned per specific action.

quiz: Contains questions, answers, and explanations linked to specific cards.

card_location & card_season: Pivot tables handling the many-to-many relationships for locations and seasonal availability.

</details>


</details>

