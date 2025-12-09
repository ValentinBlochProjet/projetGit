<!-- CORPS de la page - Homepage Accueil -->
<!-- HERO SECTION PREMIUM -->
<section class="hero-premium">
    <div class="hero-background">
        <div class="gradient-blob blob-1"></div>
        <div class="gradient-blob blob-2"></div>
        <div class="gradient-blob blob-3"></div>
    </div>
    
    <div class="hero-container">
        <div class="hero-text">
            <h1 class="hero-main-title">La Boutique du Roi</h1>
            <p class="hero-main-subtitle">Solutions Informatiques Haut de Gamme</p>
            <p class="hero-description">Découvrez notre sélection exclusive de produits premium pour tous vos besoins technologiques</p>
            <div class="hero-buttons-group">
                <a href="index.php?controleur=Produits&action=afficherTous" class="btn-hero btn-hero-primary">
                    <span>Voir nos Produits</span>
                    <span class="btn-icon">→</span>
                </a>
                <a href="index.php?controleur=client&action=afficherPanier" class="btn-hero btn-hero-outline">
                    <span>Mon Panier</span>
                </a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-image-box">
                <div class="hero-icon-large">💻</div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION CTA FINALE -->
<section class="cta-section-final">
    <div class="cta-container">
        <h2>Prêt à Trouver ce que vous Cherchez?</h2>
        <p>Parcourez notre catalogue complet</p>
        <a href="index.php?controleur=Produits&action=afficherTous" class="cta-button-final">Parcourir Maintenant</a>
    </div>
</section>

<style>
    /* RESET */
    .hero-premium,
    .advantages-premium,
    .categories-premium,
    .cta-section-final {
        margin: 0;
        padding: 0;
    }

    /* ===== HERO PREMIUM ===== */
    .hero-premium {
        position: relative;
        padding: 80px 20px 100px 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8eef7 50%, #f0f3f8 100%);
        overflow: hidden;
        min-height: 650px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        overflow: hidden;
        z-index: 0;
    }

    .gradient-blob {
        position: absolute;
        filter: blur(80px);
        opacity: 0.4;
        border-radius: 50%;
    }

    .blob-1 {
        width: 400px;
        height: 400px;
        background: linear-gradient(135deg, #1967d2, #0e3a8c);
        top: -100px;
        right: -100px;
        animation: blob-move 8s ease-in-out infinite;
    }

    .blob-2 {
        width: 350px;
        height: 350px;
        background: linear-gradient(135deg, #00bcd4, #0096d6);
        bottom: -50px;
        left: -50px;
        animation: blob-move 10s ease-in-out infinite reverse;
    }

    .blob-3 {
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        top: 50%;
        left: 50%;
        animation: blob-move 12s ease-in-out infinite;
    }

    @keyframes blob-move {
        0%, 100% { transform: translate(0, 0); }
        33% { transform: translate(50px, -50px); }
        66% { transform: translate(-30px, 30px); }
    }

    .hero-container {
        position: relative;
        z-index: 1;
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }

    .hero-text {
        animation: fadeInLeft 0.8s ease-out;
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .hero-main-title {
        font-size: 3.5em;
        font-weight: 900;
        color: #1a1a2e;
        margin-bottom: 15px;
        line-height: 1.1;
        font-family: 'Poppins', sans-serif;
        letter-spacing: -1px;
    }

    .hero-main-subtitle {
        font-size: 1.6em;
        color: #1967d2;
        font-weight: 700;
        margin-bottom: 15px;
        font-family: 'Inter', sans-serif;
    }

    .hero-description {
        font-size: 1.05em;
        color: #5a5a7a;
        margin-bottom: 40px;
        line-height: 1.7;
        font-family: 'Inter', sans-serif;
    }

    .hero-buttons-group {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn-hero {
        padding: 16px 35px;
        font-size: 1em;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.3px;
        border: none;
        cursor: pointer;
    }

    .btn-hero-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
    }

    .btn-hero-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(139, 92, 246, 0.5);
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    }

    .btn-hero-outline {
        background: white;
        color: #8b5cf6;
        border: 2px solid #8b5cf6;
    }

    .btn-hero-outline:hover {
        background: #f5f3ff;
        transform: translateY(-4px);
        border-color: #7c3aed;
        color: #7c3aed;
    }

    .btn-icon {
        font-weight: 700;
    }

    .hero-visual {
        animation: fadeInRight 0.8s ease-out;
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .hero-image-box {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, rgba(25, 103, 210, 0.15) 0%, rgba(0, 188, 212, 0.1) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(25, 103, 210, 0.2);
        box-shadow: 0 20px 50px rgba(25, 103, 210, 0.1);
    }

    .hero-icon-large {
        font-size: 150px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }





    /* ===== CTA FINAL ===== */
    .cta-section-final {
        padding: 80px 20px;
        background: linear-gradient(135deg, #1967d2 0%, #1453b8 50%, #0e3a8c 100%);
        color: white;
        text-align: center;
    }

    .cta-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .cta-section-final h2 {
        font-size: 2.5em;
        margin-bottom: 15px;
        font-weight: 900;
        font-family: 'Poppins', sans-serif;
    }

    .cta-section-final p {
        font-size: 1.15em;
        margin-bottom: 40px;
        opacity: 0.95;
        font-family: 'Inter', sans-serif;
    }

    .cta-button-final {
        display: inline-block;
        padding: 16px 45px;
        background: white;
        color: #1967d2;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.05em;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .cta-button-final:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 968px) {
        .hero-container {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .hero-premium {
            min-height: auto;
            padding: 60px 20px;
        }

        .hero-icon-large {
            font-size: 100px;
        }

        .hero-image-box {
            height: 300px;
        }

        .hero-main-title {
            font-size: 2.8em;
        }

        .advantages-title,
        .categories-header h2,
        .cta-section-final h2 {
            font-size: 2.2em;
        }
    }

    @media (max-width: 768px) {
        .hero-main-title {
            font-size: 2.2em;
        }

        .hero-main-subtitle {
            font-size: 1.3em;
        }

        .hero-description {
            font-size: 0.95em;
        }

        .hero-buttons-group {
            flex-direction: column;
        }

        .btn-hero {
            width: 100%;
            justify-content: center;
        }

        .blob-1, .blob-2, .blob-3 {
            filter: blur(60px);
            opacity: 0.3;
        }

        .categories-cards-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .advantages-grid {
            gap: 25px;
        }

        .advantage-card {
            padding: 35px 25px;
        }
    }

    @media (max-width: 480px) {
        .hero-main-title {
            font-size: 1.8em;
        }

        .hero-main-subtitle {
            font-size: 1em;
        }

        .hero-description {
            font-size: 0.9em;
            margin-bottom: 30px;
        }

        .hero-icon-large {
            font-size: 60px;
        }

        .hero-image-box {
            height: 200px;
        }

        .advantages-title,
        .categories-header h2,
        .cta-section-final h2 {
            font-size: 1.8em;
        }

        .advantage-icon-box {
            width: 70px;
            height: 70px;
        }

        .advantage-icon {
            font-size: 1.8em;
        }

        .category-card-icon {
            font-size: 2.5em;
        }

        .category-premium-card h3 {
            font-size: 1.3em;
        }

        .btn-hero {
            padding: 12px 25px;
            font-size: 0.9em;
        }

        .cta-button-final {
            padding: 14px 30px;
            font-size: 0.95em;
        }
    }
</style>