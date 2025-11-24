<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, onUnmounted } from 'vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const scrollY = ref(0);
const mouseX = ref(0);
const mouseY = ref(0);

const handleScroll = () => {
    scrollY.value = window.scrollY;

    // Parallax effect
    const parallaxElements = document.querySelectorAll('.parallax');
    parallaxElements.forEach((el: any) => {
        const speed = el.dataset.speed || 0.5;
        const yPos = -(scrollY.value * speed);
        el.style.transform = `translateY(${yPos}px)`;
    });
};

const handleMouseMove = (e: MouseEvent) => {
    mouseX.value = (e.clientX / window.innerWidth - 0.5) * 20;
    mouseY.value = (e.clientY / window.innerHeight - 0.5) * 20;

    // Update floating elements
    const floatingElements = document.querySelectorAll('.float-on-mouse');
    floatingElements.forEach((el: any, index) => {
        const speed = (index + 1) * 0.05;
        el.style.transform = `translate(${mouseX.value * speed}px, ${mouseY.value * speed}px)`;
    });
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('mousemove', handleMouseMove);

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<template>
    <Head title="Ders Programı - Yeni Nesil Ders Programı Çözümü" />

    <div class="vision-page">
        <!-- Navigation -->
        <nav class="vision-nav">
            <div class="vision-nav-content">
                <div class="nav-logo">
                    <span class="logo-text">Ders Programı</span>
                </div>
                <div class="nav-links">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="nav-button nav-button-primary"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="nav-link"
                        >
                            Giriş Yap
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-background">
                <div class="gradient-orb orb-1 parallax" data-speed="0.3"></div>
                <div class="gradient-orb orb-2 parallax" data-speed="0.5"></div>
                <div class="gradient-orb orb-3 parallax" data-speed="0.4"></div>
                <div class="glass-grid"></div>
            </div>

            <div class="hero-content">
                <div class="hero-badge float-on-mouse">
                    <span class="badge-dot"></span>
                    Yapay Zeka Destekli
                </div>

                <h1 class="hero-title">
                    <span class="title-main">Ders Programı</span>
                    <span class="title-sub">Geleceği bugün yaşayın</span>
                </h1>

                <p class="hero-description">
                    Genetik algoritma ve yapay zeka ile optimize edilmiş,<br class="hide-mobile">
                    tamamen otomatik ders programı oluşturma deneyimi.
                </p>

                <div class="hero-actions">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="register()"
                        class="action-button primary-button"
                    >
                        <span>Hemen Başla</span>
                        <div class="button-glow"></div>
                    </Link>
                    <Link
                        v-else
                        :href="dashboard()"
                        class="action-button primary-button"
                    >
                        <span>Dashboard</span>
                        <div class="button-glow"></div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Feature Section 1 -->
        <section class="feature-section animate-on-scroll">
            <div class="section-background">
                <div class="gradient-orb orb-4"></div>
            </div>
            <div class="feature-content">
                <div class="feature-header">
                    <span class="feature-label">Genetik Algoritma</span>
                    <h2 class="feature-title">
                        Akıllı optimizasyon.<br>
                        <span class="gradient-text">Saniyeler içinde.</span>
                    </h2>
                </div>
                <p class="feature-description">
                    Öğretmen müsaitliği, mekan kapasitesi ve öğrenci gruplarını analiz ederek<br class="hide-mobile">
                    en optimal programları otomatik olarak oluşturur.
                </p>

                <div class="feature-visual">
                    <div class="visual-container">
                        <div class="visual-card glass-effect">
                            <div class="visual-shimmer"></div>
                            <div class="visual-content">
                                <div class="progress-ring">
                                    <svg viewBox="0 0 200 200">
                                        <circle cx="100" cy="100" r="90" class="ring-background"/>
                                        <circle cx="100" cy="100" r="90" class="ring-progress"/>
                                    </svg>
                                    <div class="ring-center">
                                        <div class="ring-value">99.8%</div>
                                        <div class="ring-label">Optimal</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Section 2 - Dark -->
        <section class="feature-section dark-section animate-on-scroll">
            <div class="section-background-dark">
                <div class="gradient-orb orb-5"></div>
                <div class="gradient-orb orb-6"></div>
            </div>
            <div class="feature-content">
                <div class="feature-header">
                    <span class="feature-label light">Kısıtlama Yönetimi</span>
                    <h2 class="feature-title light">
                        Karmaşıklığı<br>
                        <span class="gradient-text-alt">basitliğe çevirin.</span>
                    </h2>
                </div>
                <p class="feature-description light">
                    Tüm kısıtlamaları tek bir platformda yönetin.<br class="hide-mobile">
                    Sistem otomatik olarak tüm kuralları uygular.
                </p>

                <div class="feature-grid">
                    <div class="grid-card glass-effect">
                        <div class="grid-icon">📅</div>
                        <h3 class="grid-title">Zaman Yönetimi</h3>
                        <p class="grid-text">Esnek zaman dilimi tanımlaması</p>
                    </div>
                    <div class="grid-card glass-effect">
                        <div class="grid-icon">🏢</div>
                        <h3 class="grid-title">Mekan Planı</h3>
                        <p class="grid-text">Akıllı kapasite optimizasyonu</p>
                    </div>
                    <div class="grid-card glass-effect">
                        <div class="grid-icon">👥</div>
                        <h3 class="grid-title">Grup Sistemi</h3>
                        <p class="grid-text">Otomatik çakışma engelleme</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Section 3 -->
        <section class="feature-section animate-on-scroll">
            <div class="section-background">
                <div class="gradient-orb orb-7"></div>
            </div>
            <div class="feature-content">
                <div class="feature-header">
                    <span class="feature-label">Entegrasyon</span>
                    <h2 class="feature-title">
                        Excel & PDF.<br>
                        <span class="gradient-text">Sorunsuz aktarım.</span>
                    </h2>
                </div>
                <p class="feature-description">
                    Verilerinizi kolayca içe aktarın, programlarınızı<br class="hide-mobile">
                    istediğiniz formatta dışa aktarın.
                </p>

                <div class="integration-showcase">
                    <div class="showcase-item glass-effect">
                        <div class="showcase-glow"></div>
                        <div class="showcase-icon-wrapper">
                            <div class="showcase-icon excel-icon">
                                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                                    <rect width="64" height="64" rx="12" fill="url(#excel-gradient)"/>
                                    <path d="M22 22L42 42M42 22L22 42" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                    <defs>
                                        <linearGradient id="excel-gradient">
                                            <stop offset="0%" stop-color="#1D6F42"/>
                                            <stop offset="100%" stop-color="#34A853"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                        </div>
                        <h3 class="showcase-title">Excel İçe Aktarım</h3>
                        <p class="showcase-text">Tüm verilerinizi hızlıca yükleyin</p>
                    </div>

                    <div class="showcase-item glass-effect">
                        <div class="showcase-glow"></div>
                        <div class="showcase-icon-wrapper">
                            <div class="showcase-icon pdf-icon">
                                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                                    <rect width="64" height="64" rx="12" fill="url(#pdf-gradient)"/>
                                    <text x="32" y="40" font-size="20" font-weight="bold" fill="white" text-anchor="middle">PDF</text>
                                    <defs>
                                        <linearGradient id="pdf-gradient">
                                            <stop offset="0%" stop-color="#DC4437"/>
                                            <stop offset="100%" stop-color="#F55742"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                        </div>
                        <h3 class="showcase-title">PDF Dışa Aktarım</h3>
                        <p class="showcase-text">Profesyonel çıktı alın</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section animate-on-scroll">
            <div class="cta-background">
                <div class="gradient-orb orb-8"></div>
                <div class="gradient-orb orb-9"></div>
            </div>
            <div class="cta-content">
                <h2 class="cta-title">
                    Hazır mısınız?
                </h2>
                <p class="cta-description">
                    Ders programı oluşturmayı yeniden keşfedin.
                </p>
                <Link
                    v-if="!$page.props.auth.user"
                    :href="register()"
                    class="cta-button"
                >
                    <span>Ücretsiz Başla</span>
                    <div class="button-glow"></div>
                </Link>
                <Link
                    v-else
                    :href="dashboard()"
                    class="cta-button"
                >
                    <span>Dashboard'a Git</span>
                    <div class="button-glow"></div>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="vision-footer">
            <div class="footer-content">
                <p class="footer-text">© 2025 Ders Programı. Tüm hakları saklıdır.</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

.vision-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background: #000;
    color: #f5f5f7;
    overflow-x: hidden;
}

/* Navigation */
.vision-nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(40px) saturate(180%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.vision-nav-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px;
    height: 52px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-logo {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.logo-text {
    background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.nav-links {
    display: flex;
    gap: 32px;
    align-items: center;
}

.nav-link {
    font-size: 15px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #fff;
}

.nav-button {
    padding: 10px 20px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-button-primary {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.nav-button-primary:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.05);
}

/* Hero Section */
.hero-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 100px 40px 80px;
}

.hero-background {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.5;
    pointer-events: none;
}

.orb-1 {
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, #667eea 0%, transparent 70%);
    top: -400px;
    right: -200px;
    animation: float 25s ease-in-out infinite;
}

.orb-2 {
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, #f093fb 0%, transparent 70%);
    bottom: -300px;
    left: -100px;
    animation: float 20s ease-in-out infinite reverse;
}

.orb-3 {
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, #4facfe 0%, transparent 70%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: float 30s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translate(0, 0);
    }
    25% {
        transform: translate(50px, -50px);
    }
    50% {
        transform: translate(-30px, 30px);
    }
    75% {
        transform: translate(30px, -30px);
    }
}

.glass-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
    mask-image: radial-gradient(ellipse at center, black 20%, transparent 70%);
}

.hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    max-width: 1000px;
    margin: 0 auto;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 100px;
    font-size: 14px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 40px;
    opacity: 0;
    animation: fadeInUp 1s ease forwards 0.2s;
}

.badge-dot {
    width: 8px;
    height: 8px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    animation: pulse 2s ease infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.2);
    }
}

.hero-title {
    margin-bottom: 30px;
}

.title-main {
    display: block;
    font-size: 96px;
    font-weight: 900;
    letter-spacing: -2px;
    line-height: 1;
    background: linear-gradient(135deg, #ffffff 0%, rgba(255,255,255,0.5) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 16px;
    opacity: 0;
    animation: fadeInUp 1s ease forwards 0.4s;
}

.title-sub {
    display: block;
    font-size: 56px;
    font-weight: 300;
    letter-spacing: -1px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-size: 200% auto;
    animation: fadeInUp 1s ease forwards 0.6s, shimmer 3s ease infinite;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shimmer {
    0%, 100% {
        background-position: 0% center;
    }
    50% {
        background-position: 200% center;
    }
}

.hero-description {
    font-size: 24px;
    font-weight: 400;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 50px;
    opacity: 0;
    animation: fadeInUp 1s ease forwards 0.8s;
}

.hero-actions {
    opacity: 0;
    animation: fadeInUp 1s ease forwards 1s;
}

.action-button {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 48px;
    font-size: 18px;
    font-weight: 600;
    text-decoration: none;
    color: #fff;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 100px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.button-glow {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.4), rgba(118, 75, 162, 0.4));
    opacity: 0;
    transition: opacity 0.4s ease;
}

.action-button:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
    border-color: rgba(255, 255, 255, 0.4);
}

.action-button:hover .button-glow {
    opacity: 1;
}

.action-button span {
    position: relative;
    z-index: 1;
}

/* Floating Showcase */
.floating-showcase {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.glass-card {
    position: absolute;
    padding: 24px 28px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(40px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    opacity: 0;
    animation: fadeInCard 1s ease forwards;
    transition: transform 0.1s ease;
}

.card-1 {
    top: 15%;
    right: 10%;
    animation-delay: 1.2s;
}

.card-2 {
    bottom: 25%;
    left: 8%;
    animation-delay: 1.4s;
}

.card-3 {
    top: 45%;
    right: 12%;
    animation-delay: 1.6s;
}

@keyframes fadeInCard {
    to {
        opacity: 1;
    }
}

.card-glow {
    position: absolute;
    inset: -100%;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
}

.glass-card:hover .card-glow {
    opacity: 1;
}

.card-content {
    position: relative;
    z-index: 1;
    text-align: center;
}

.card-icon {
    font-size: 32px;
    margin-bottom: 8px;
}

.card-stat {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
}

.card-label {
    font-size: 13px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.6);
}

/* Feature Sections */
.feature-section {
    position: relative;
    padding: 180px 40px;
    overflow: hidden;
}

.section-background {
    position: absolute;
    inset: 0;
    z-index: 0;
    background: #000;
}

.section-background-dark {
    position: absolute;
    inset: 0;
    z-index: 0;
    background: linear-gradient(180deg, #000 0%, #0a0a0a 100%);
}

.orb-4 {
    width: 700px;
    height: 700px;
    background: radial-gradient(circle, #667eea 0%, transparent 70%);
    top: -300px;
    right: -200px;
}

.orb-5 {
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, #f093fb 0%, transparent 70%);
    bottom: -400px;
    left: -300px;
}

.orb-6 {
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, #4facfe 0%, transparent 70%);
    top: 20%;
    right: 10%;
}

.orb-7 {
    width: 750px;
    height: 750px;
    background: radial-gradient(circle, #43e97b 0%, transparent 70%);
    top: -350px;
    left: -250px;
}

.orb-8 {
    width: 900px;
    height: 900px;
    background: radial-gradient(circle, #667eea 0%, transparent 70%);
    top: -450px;
    left: 50%;
    transform: translateX(-50%);
}

.orb-9 {
    width: 700px;
    height: 700px;
    background: radial-gradient(circle, #f093fb 0%, transparent 70%);
    bottom: -350px;
    right: -200px;
}

.feature-content {
    position: relative;
    z-index: 1;
    max-width: 1000px;
    margin: 0 auto;
    text-align: center;
}

.feature-header {
    margin-bottom: 40px;
}

.feature-label {
    display: inline-block;
    padding: 8px 18px;
    background: rgba(102, 126, 234, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(102, 126, 234, 0.3);
    border-radius: 100px;
    font-size: 14px;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 24px;
}

.feature-label.light {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.9);
}

.feature-title {
    font-size: 72px;
    font-weight: 900;
    letter-spacing: -1.5px;
    line-height: 1.1;
    color: #fff;
    margin-bottom: 24px;
}

.feature-title.light {
    color: #fff;
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.gradient-text-alt {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.feature-description {
    font-size: 22px;
    font-weight: 400;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 80px;
}

.feature-description.light {
    color: rgba(255, 255, 255, 0.6);
}

/* Scroll Animations */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(60px);
    transition: all 1.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-on-scroll.animate-in {
    opacity: 1;
    transform: translateY(0);
}

/* Visual Container */
.feature-visual {
    margin-top: 80px;
}

.visual-container {
    max-width: 500px;
    margin: 0 auto;
}

.visual-card {
    position: relative;
    padding: 60px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(40px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 32px;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.visual-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 40px 80px rgba(102, 126, 234, 0.2);
}

.glass-effect {
    position: relative;
    overflow: hidden;
}

.glass-effect::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.glass-effect:hover::before {
    opacity: 1;
}

.visual-shimmer {
    position: absolute;
    inset: -100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shimmerMove 4s infinite;
}

@keyframes shimmerMove {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

.progress-ring {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto;
}

.progress-ring svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.ring-background {
    fill: none;
    stroke: rgba(255, 255, 255, 0.1);
    stroke-width: 8;
}

.ring-progress {
    fill: none;
    stroke: url(#ringGradient);
    stroke-width: 8;
    stroke-linecap: round;
    stroke-dasharray: 565;
    stroke-dashoffset: 28;
    animation: ringFill 2s ease forwards;
}

@keyframes ringFill {
    from {
        stroke-dashoffset: 565;
    }
    to {
        stroke-dashoffset: 28;
    }
}

.ring-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.ring-value {
    font-size: 48px;
    font-weight: 900;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ring-label {
    font-size: 16px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 4px;
}

/* Feature Grid */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 32px;
    margin-top: 80px;
}

.grid-card {
    padding: 48px 36px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(40px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    text-align: center;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.grid-card:hover {
    transform: translateY(-12px);
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 30px 60px rgba(102, 126, 234, 0.2);
}

.grid-icon {
    font-size: 56px;
    margin-bottom: 24px;
}

.grid-title {
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 12px;
}

.grid-text {
    font-size: 16px;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.6);
}

/* Integration Showcase */
.integration-showcase {
    display: flex;
    justify-content: center;
    gap: 48px;
    margin-top: 80px;
    flex-wrap: wrap;
}

.showcase-item {
    position: relative;
    padding: 48px 40px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(40px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 28px;
    text-align: center;
    min-width: 280px;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.showcase-item:hover {
    transform: translateY(-12px) scale(1.02);
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 40px 80px rgba(102, 126, 234, 0.3);
}

.showcase-glow {
    position: absolute;
    inset: -50%;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.3), transparent 60%);
    opacity: 0;
    transition: opacity 0.6s ease;
}

.showcase-item:hover .showcase-glow {
    opacity: 1;
}

.showcase-icon-wrapper {
    margin-bottom: 28px;
}

.showcase-icon {
    display: inline-block;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.showcase-item:hover .showcase-icon {
    transform: scale(1.1) rotate(-5deg);
}

.showcase-title {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 12px;
}

.showcase-text {
    font-size: 16px;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.6);
}

/* CTA Section */
.cta-section {
    position: relative;
    padding: 200px 40px;
    overflow: hidden;
}

.cta-background {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.cta-content {
    position: relative;
    z-index: 1;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.cta-title {
    font-size: 96px;
    font-weight: 900;
    letter-spacing: -2px;
    background: linear-gradient(135deg, #ffffff 0%, rgba(255,255,255,0.6) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 24px;
}

.cta-description {
    font-size: 28px;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 60px;
}

.cta-button {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 22px 56px;
    font-size: 20px;
    font-weight: 700;
    text-decoration: none;
    color: #fff;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 100px;
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.cta-button:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 30px 80px rgba(102, 126, 234, 0.5);
    border-color: rgba(255, 255, 255, 0.4);
}

.cta-button:hover .button-glow {
    opacity: 1;
}

.cta-button span {
    position: relative;
    z-index: 1;
}

/* Footer */
.vision-footer {
    padding: 60px 40px;
    text-align: center;
    background: rgba(255, 255, 255, 0.02);
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.footer-text {
    font-size: 14px;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.4);
}

/* Responsive */
@media (max-width: 1024px) {
    .floating-showcase {
        display: none;
    }
}

@media (max-width: 768px) {
    .title-main {
        font-size: 64px;
    }

    .title-sub {
        font-size: 40px;
    }

    .hero-description {
        font-size: 20px;
    }

    .feature-title {
        font-size: 48px;
    }

    .feature-description {
        font-size: 18px;
    }

    .cta-title {
        font-size: 64px;
    }

    .cta-description {
        font-size: 22px;
    }

    .hide-mobile {
        display: none;
    }

    .feature-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 80px 24px 60px;
    }

    .title-main {
        font-size: 48px;
    }

    .title-sub {
        font-size: 32px;
    }

    .hero-description {
        font-size: 18px;
    }

    .feature-section {
        padding: 120px 24px;
    }

    .feature-title {
        font-size: 36px;
    }

    .feature-description {
        font-size: 16px;
    }

    .cta-section {
        padding: 140px 24px;
    }

    .cta-title {
        font-size: 48px;
    }

    .cta-description {
        font-size: 20px;
    }

    .action-button,
    .cta-button {
        padding: 16px 36px;
        font-size: 16px;
    }
}

/* SVG Gradients */
svg defs {
    width: 0;
    height: 0;
    position: absolute;
}
</style>
