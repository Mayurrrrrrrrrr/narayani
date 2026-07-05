<?php
declare(strict_types=1);

// Custom command-line migration & seeder engine for Narayani Portal
require_once __DIR__ . '/../src/Services/Database.php';

use App\Services\Database;

try {
    echo "=== Narayani Portal Database Migration & Seeding ===\n";
    $pdo = Database::getConnection();
    echo "[OK] Connected to database successfully.\n";

    // Disable foreign key checks to drop tables cleanly
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // Drop tables if they exist
    $tables = [
        'leads',
        'blog_posts',
        'testimonials',
        'payments',
        'bookings',
        'services',
        'service_categories',
        'consultant_profile',
        'users'
    ];

    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `{$table}`;");
        echo "[INFO] Dropped table (if existed): {$table}\n";
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // 1. Create users table
    $pdo->exec("
        CREATE TABLE `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) UNIQUE NOT NULL,
            `phone` VARCHAR(50) NULL,
            `city` VARCHAR(100) NULL,
            `password_hash` VARCHAR(255) NOT NULL,
            `google_id` VARCHAR(255) NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: users\n";

    // 2. Create consultant_profile table
    $pdo->exec("
        CREATE TABLE `consultant_profile` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `tagline_en` VARCHAR(255) NULL,
            `tagline_hi` VARCHAR(255) NULL,
            `photo_url` VARCHAR(255) NULL,
            `bio_en` TEXT NULL,
            `bio_hi` TEXT NULL,
            `credentials` JSON NULL,
            `modes` JSON NULL,
            `weekly_availability` JSON NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: consultant_profile\n";

    // 3. Create service_categories table
    $pdo->exec("
        CREATE TABLE `service_categories` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `slug` VARCHAR(100) UNIQUE NOT NULL,
            `name_en` VARCHAR(255) NOT NULL,
            `name_hi` VARCHAR(255) NOT NULL,
            `description_en` TEXT NULL,
            `description_hi` TEXT NULL,
            `icon_type` VARCHAR(100) NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: service_categories\n";

    // 4. Create services table
    $pdo->exec("
        CREATE TABLE `services` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `category_id` INT NOT NULL,
            `slug` VARCHAR(100) UNIQUE NOT NULL,
            `title_en` VARCHAR(255) NOT NULL,
            `title_hi` VARCHAR(255) NOT NULL,
            `short_desc_en` TEXT NULL,
            `short_desc_hi` TEXT NULL,
            `long_desc_en` TEXT NULL,
            `long_desc_hi` TEXT NULL,
            `duration` INT NOT NULL COMMENT 'duration in minutes',
            `price_inr` DECIMAL(10,2) NOT NULL,
            `is_active` TINYINT(1) DEFAULT 1,
            FOREIGN KEY (`category_id`) REFERENCES `service_categories`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: services\n";

    // 5. Create bookings table
    $pdo->exec("
        CREATE TABLE `bookings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `service_id` INT NOT NULL,
            `scheduled_at` DATETIME NOT NULL,
            `status` ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
            `consultation_mode` VARCHAR(100) NOT NULL,
            `intake_data` JSON NULL,
            `payment_id` VARCHAR(255) NULL,
            `report_path` VARCHAR(255) NULL,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: bookings\n";

    // 6. Create payments table
    $pdo->exec("
        CREATE TABLE `payments` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `booking_id` INT NOT NULL,
            `gateway_order_id` VARCHAR(255) NOT NULL,
            `gateway_payment_id` VARCHAR(255) NULL,
            `amount` DECIMAL(10,2) NOT NULL,
            `status` VARCHAR(100) NOT NULL,
            `raw_payload` JSON NULL,
            FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: payments\n";

    // 7. Create testimonials table
    $pdo->exec("
        CREATE TABLE `testimonials` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `client_name` VARCHAR(255) NOT NULL,
            `client_city` VARCHAR(100) NULL,
            `rating` INT NOT NULL DEFAULT 5,
            `content_en` TEXT NOT NULL,
            `content_hi` TEXT NOT NULL,
            `is_featured` TINYINT(1) DEFAULT 0,
            `is_approved` TINYINT(1) DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: testimonials\n";

    // 8. Create blog_posts table
    $pdo->exec("
        CREATE TABLE `blog_posts` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `category_id` INT NULL,
            `title_en` VARCHAR(255) NOT NULL,
            `title_hi` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(100) UNIQUE NOT NULL,
            `excerpt_en` TEXT NULL,
            `excerpt_hi` TEXT NULL,
            `content_en` TEXT NULL,
            `content_hi` TEXT NULL,
            `cover_image` VARCHAR(255) NULL,
            `is_published` TINYINT(1) DEFAULT 0,
            `published_at` DATETIME NULL,
            FOREIGN KEY (`category_id`) REFERENCES `service_categories`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: blog_posts\n";

    // 9. Create leads table
    $pdo->exec("
        CREATE TABLE `leads` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(50) NULL,
            `email` VARCHAR(255) NULL,
            `source` VARCHAR(100) NULL,
            `message` TEXT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[OK] Created table: leads\n";

    echo "=== Seeding Tables ===\n";

    // Seed User
    $hashedPassword = password_hash('Narayani@2026', PASSWORD_BCRYPT);
    $stmtUser = $pdo->prepare("INSERT INTO `users` (name, email, phone, city, password_hash) VALUES (?, ?, ?, ?, ?)");
    $stmtUser->execute(['Mayur Acharya', 'mayur@narayani.com', '9876543210', 'Bangalore', $hashedPassword]);
    echo "[SEED] Inserted initial user.\n";

    // Seed Consultant Profile
    $credentials = json_encode(['Vastu Shastra Acharya', 'Jyotish Ratna', 'M.Sc Applied Cosmology']);
    $modes = json_encode(['Video Call', 'On-Site Visit', 'Audio Consultation']);
    $availability = json_encode([
        'Monday' => ['10:00-12:00', '14:00-16:00'],
        'Wednesday' => ['10:00-12:00', '15:00-18:00'],
        'Friday' => ['09:00-12:00']
    ]);
    $stmtConsultant = $pdo->prepare("
        INSERT INTO `consultant_profile` (name, tagline_en, tagline_hi, photo_url, bio_en, bio_hi, credentials, modes, weekly_availability)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmtConsultant->execute([
        'Acharya Vinay Dev',
        'Aligning spatial geometries with cosmic rhythms.',
        'ब्रह्मांडीय लय के साथ स्थानिक ज्यामिति का संरेखण।',
        '/generate-asset?type=placeholder&w=300&h=300&text=Acharya+Vinay',
        'Acharya Vinay has over 15 years of experience in Vedic Vastu auditing, astrological alignments, and cosmological geometry consultations.',
        'आचार्य विनय को वैदिक वास्तु ऑडिटिंग, ज्योतिषीय संरेखण और ब्रह्मांडीय ज्यामिति परामर्श में 15 से अधिक वर्षों का अनुभव है।',
        $credentials,
        $modes,
        $availability
    ]);
    echo "[SEED] Inserted consultant profile.\n";

    // Seed Categories
    $stmtCat = $pdo->prepare("INSERT INTO `service_categories` (slug, name_en, name_hi, description_en, description_hi, icon_type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtCat->execute(['vastu', 'Vastu Auditing', 'वास्तु ऑडिटिंग', 'Harmonize your living and workspaces with cosmic alignment principles.', 'पवित्र संरेखण सिद्धांतों के साथ अपने रहने और कार्यक्षेत्रों को सामंजस्यपूर्ण बनाएं।', 'vastu']);
    $stmtCat->execute(['jyotish', 'Cosmic Jyotish', 'ब्रह्मांडीय ज्योतिष', 'Map planetary transits and natal positions to discover your spiritual trajectory.', 'अपनी आध्यात्मिक दिशा का पता लगाने के लिए ग्रहों के गोचर और जन्म कुंडली की स्थिति का नक्शा बनाएं।', 'astrology']);
    $stmtCat->execute(['healing', 'Spiritual Healing', 'ब्रह्मांडीय हीलिंग', 'Explore cosmological patterns, divine proportions, and energy healing sessions.', 'ब्रह्मांडीय पैटर्न, दिव्य अनुपात और ऊर्जा उपचार सत्रों का अन्वेषण करें।', 'geometry']);
    echo "[SEED] Inserted service categories.\n";

    $vastuCatId = $pdo->lastInsertId() - 2; // Category 1: Vastu
    $jyotishCatId = $pdo->lastInsertId() - 1; // Category 2: Jyotish
    $healingCatId = $pdo->lastInsertId(); // Category 3: Healing

    // Seed Services
    $stmtService = $pdo->prepare("INSERT INTO `services` (category_id, slug, title_en, title_hi, short_desc_en, short_desc_hi, long_desc_en, long_desc_hi, duration, price_inr, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmtService->execute([
        $vastuCatId,
        'vastu-residential',
        'Residential Vastu Audit',
        'आवासीय वास्तु ऑडिट',
        'Align your home spatial flow with structural and directional guidelines.',
        'संरचनात्मक और दिशात्मक दिशानिर्देशों के साथ अपने घर के स्थानिक प्रवाह को संरेखित करें।',
        'A comprehensive evaluation of your residential space to eliminate spatial blockages and restore cosmic energy flow.',
        'स्थानिक रुकावटों को खत्म करने और ब्रह्मांडीय ऊर्जा प्रवाह को बहाल करने के लिए आपके आवासीय स्थान का एक व्यापक मूल्यांकन।',
        90,
        5100.00,
        1
    ]);
    
    $stmtService->execute([
        $vastuCatId,
        'vastu-commercial',
        'Commercial Space Vastu Harmonization',
        'व्यावसायिक स्थान वास्तु सामंजस्य',
        'Optimize energy flow in commercial buildings to maximize prosperity.',
        'समृद्धि को अधिकतम करने के लिए व्यावसायिक भवनों में ऊर्जा प्रवाह को अनुकूलित करें।',
        'Apply sacred geometric and structural corrections to business offices, shops, or factory spaces.',
        'व्यापारिक कार्यालयों, दुकानों या कारखाने के स्थानों पर पवित्र ज्यामितीय और संरचनात्मक सुधार लागू करें।',
        120,
        10800.00,
        1
    ]);

    $stmtService->execute([
        $jyotishCatId,
        'natal-resonance',
        'Natal Resonance Alignment',
        'जन्म अनुनाद संरेखण',
        'Discover cosmic influences based on Vedic birth chart tracking.',
        'वैदिक जन्म कुंडली ट्रैकिंग के आधार पर ब्रह्मांडीय प्रभावों की खोज करें।',
        'Explore planetary structures and transit parameters to locate spatial wellness patterns mapped specifically to you.',
        'विशेष रूप से आपके लिए मैप किए गए स्थानिक कल्याण पैटर्न का पता लगाने के लिए ग्रहों की संरचनाओं और गोचर मापदंडों का अन्वेषण करें।',
        60,
        2100.00,
        1
    ]);

    $stmtService->execute([
        $healingCatId,
        'sacred-yantra',
        'Sacred Sri Yantra Integration',
        'पवित्र श्री यंत्र एकीकरण',
        'Integrate mathematical geometry and lotus motifs in your business workspace.',
        'अपने व्यावसायिक कार्यक्षेत्र में गणितीय ज्यामिति और कमल रूपांकनों को एकीकृत करें।',
        'A dynamic mathematical session overlaying coordinates of Vedic Sri Yantras onto commercial properties to invite abundance.',
        'प्रचुरता को आमंत्रित करने के लिए व्यावसायिक संपत्तियों पर वैदिक श्री यंत्रों के निर्देशांक को ओवरले करने वाला एक गतिशील गणितीय सत्र।',
        60,
        3500.00,
        1
    ]);
    echo "[SEED] Inserted wellness services.\n";

    // Seed Testimonials
    $stmtTestimonial = $pdo->prepare("INSERT INTO `testimonials` (client_name, client_city, rating, content_en, content_hi, is_featured, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtTestimonial->execute([
        'Amit Sharma',
        'Mumbai',
        5,
        'The residential Vastu recommendations transformed the energy flow in my apartment completely.',
        'आवासीय वास्तु सिफारिशों ने मेरे अपार्टमेंट में ऊर्जा प्रवाह को पूरी तरह से बदल दिया।',
        1,
        1
    ]);
    $stmtTestimonial->execute([
        'Priya Nair',
        'Kochi',
        5,
        'The dynamic SVG mandalas generated are absolute masterworks of sacred geometries.',
        'उत्पन्न किए गए गतिशील एसवीजी मंडल पवित्र ज्यामिति की उत्कृष्ट कृतियाँ हैं।',
        1,
        1
    ]);
    echo "[SEED] Inserted pre-approved testimonials.\n";

    // Seed Blogs
    $stmtBlog = $pdo->prepare("INSERT INTO `blog_posts` (category_id, title_en, title_hi, slug, excerpt_en, excerpt_hi, content_en, content_hi, cover_image, is_published, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtBlog->execute([
        $healingCatId,
        'Decoding the Starfields of Sacred Mandalas',
        'पवित्र मंडलों के ताराक्षेत्रों को डिकोड करना',
        'decoding-starfields-mandalas',
        'An exploration of mathematical coordinates and sacred geometry motifs.',
        'गणितीय निर्देशांक और पवित्र ज्यामिति रूपांकनों का एक अन्वेषण।',
        '<h2>Sacred Sri Yantra Coordinates</h2><p>Sacred geometry maps mathematical equations directly onto visual art forms. In this piece, we explore the algorithms behind the Sri Yantra and concentric mandalas...</p><h2>Visualizing Concentric Lattices</h2><p>The stellar convergence tracks alignment nodes matching consultant guidelines...</p>',
        '<h2>पवित्र श्री यंत्र निर्देशांक</h2><p>पवित्र ज्यामिति गणितीय समीकरणों को सीधे दृश्य कला रूपों पर मैप करती है। इस लेख में, हम श्री यंत्र और संकेंद्रित मंडलों के पीछे के एल्गोरिदम का पता लगाते हैं...</p><h2>संकेंद्रित जालियों की कल्पना करना</h2><p>तारकीय अभिसरण सलाहकार दिशानिर्देशों से मेल खाने वाले संरेखण नोड्स को ट्रैक करता है...</p>',
        '/generate-asset?type=geometry&seed=mandalas&w=600',
        1,
        date('Y-m-d H:i:s')
    ]);
    echo "[SEED] Inserted initial blog post.\n";

    echo "[SUCCESS] Migration and Seeding finished successfully!\n";

} catch (Exception $e) {
    echo "[ERROR] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
