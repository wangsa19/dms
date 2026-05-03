import "./bootstrap";

// --- Logic Theme ---
const applySavedTheme = () => {
    const storedTheme = localStorage.getItem("color-theme");
    const shouldUseDark =
        storedTheme === "dark" ||
        (!storedTheme &&
            window.matchMedia("(prefers-color-scheme: dark)").matches);

    document.documentElement.classList.toggle("dark", shouldUseDark);
};

const initThemeToggle = () => {
    applySavedTheme();
    const themeToggleBtn = document.getElementById("theme-toggle");
    const themeToggleDarkIcon = document.getElementById(
        "theme-toggle-dark-icon",
    );
    const themeToggleLightIcon = document.getElementById(
        "theme-toggle-light-icon",
    );

    const syncThemeToggleUI = () => {
        const isDark = document.documentElement.classList.contains("dark");
        if (themeToggleDarkIcon)
            themeToggleDarkIcon.classList.toggle("hidden", isDark);
        if (themeToggleLightIcon)
            themeToggleLightIcon.classList.toggle("hidden", !isDark);
        if (themeToggleBtn) {
            const label = isDark
                ? "Switch to light mode"
                : "Switch to dark mode";
            themeToggleBtn.setAttribute("aria-label", label);
        }
    };

    syncThemeToggleUI();

    if (themeToggleBtn && !themeToggleBtn.dataset.themeBound) {
        themeToggleBtn.dataset.themeBound = "true";
        themeToggleBtn.addEventListener("click", () => {
            const isDark = document.documentElement.classList.toggle("dark");
            localStorage.setItem("color-theme", isDark ? "dark" : "light");
            syncThemeToggleUI();
        });
    }
};

const renderDynamicChart = (selector, options) => {
    const el = document.querySelector(selector);
    if (!el || typeof ApexCharts === "undefined") return;

    // PENTING: Kosongkan elemen agar tidak menumpuk saat fungsi dipanggil berkali-kali
    el.innerHTML = "";

    const labels = JSON.parse(el.dataset.labels || "[]");
    const series = JSON.parse(el.dataset.series || "[]");

    const config = {
        ...options,
        series: series,
        labels: labels,
    };

    const chart = new ApexCharts(el, config);
    chart.render();
};

const initCharts = () => {
    const pieColors = ["#e74c3c", "#1abc9c", "#f1c40f", "#3498db", "#9b59b6"];

    renderDynamicChart("#category-chart", {
        chart: { type: "pie", height: 320 },
        colors: pieColors, // Ini sudah benar
        legend: { position: "bottom" },
    });

    renderDynamicChart("#type-chart", {
        chart: { type: "pie", height: 320 },
        colors: pieColors, // Cukup panggil variabelnya saja, jangan dibungkus []
        legend: { position: "bottom" },
    });

    renderDynamicChart("#department-chart", {
        chart: { type: "donut", height: 320 },
        colors: pieColors, // Cukup panggil variabelnya saja
        legend: { position: "bottom" },
    });

    const lineEl = document.querySelector("#docs-out-return-chart");
    if (lineEl) {
        lineEl.innerHTML = "";
        const labels = JSON.parse(lineEl.dataset.labels || "[]");
        const series = JSON.parse(lineEl.dataset.series || "[]");

        new ApexCharts(lineEl, {
            series: series,
            chart: { type: "line", height: 350, toolbar: { show: false } },
            stroke: { curve: "smooth", width: 3 },
            xaxis: { categories: labels },
            colors: ["#e74c3c", "#1abc9c"], // Merah untuk Out, Hijau untuk Return
            legend: { position: "top" },
        }).render();
    }
};

// --- Inisialisasi Aplikasi ---
const initAppEnhancements = () => {
    initThemeToggle();
    initCharts();
};

document.addEventListener("DOMContentLoaded", initAppEnhancements);
document.addEventListener("livewire:navigated", initAppEnhancements);
