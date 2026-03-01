import "./bootstrap";

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
        "theme-toggle-dark-icon"
    );
    const themeToggleLightIcon = document.getElementById(
        "theme-toggle-light-icon"
    );

    const syncThemeToggleUI = () => {
        const isDark = document.documentElement.classList.contains("dark");

        if (themeToggleDarkIcon) {
            themeToggleDarkIcon.classList.toggle("hidden", isDark);
        }
        if (themeToggleLightIcon) {
            themeToggleLightIcon.classList.toggle("hidden", !isDark);
        }

        if (themeToggleBtn) {
            const nextModeLabel = isDark
                ? "Switch to light mode"
                : "Switch to dark mode";
            themeToggleBtn.setAttribute("aria-label", nextModeLabel);
            themeToggleBtn.setAttribute("title", nextModeLabel);
        }
    };

    syncThemeToggleUI();

    if (themeToggleBtn && !themeToggleBtn.dataset.themeBound) {
        themeToggleBtn.dataset.themeBound = "true";

        themeToggleBtn.addEventListener("click", function () {
            const isDark = document.documentElement.classList.contains("dark");
            const nextIsDark = !isDark;

            document.documentElement.classList.toggle("dark", nextIsDark);
            localStorage.setItem("color-theme", nextIsDark ? "dark" : "light");

            syncThemeToggleUI();
        });
    }
};

const initAppEnhancements = () => {
    initThemeToggle();

    // --- Logika untuk Sidebar (akan aktif di semua halaman) ---
    // const menuIcon = document.querySelector(".main-header .menu-icon");
    // const sidebarOverlay = document.querySelector(".sidebar-overlay");
    // const body = document.body;

    // if (menuIcon) {
    //     menuIcon.addEventListener("click", () => {
    //         // Cek lebar layar saat ikon diklik
    //         if (window.innerWidth <= 1024) {
    //             // Logika untuk mobile: buka/tutup dengan overlay
    //             body.classList.toggle("sidebar-mobile-open");
    //         } else {
    //             // Logika untuk desktop: ciutkan/lebarkan
    //             body.classList.toggle("sidebar-collapsed");
    //         }
    //     });
    // }

    // if (sidebarOverlay) {
    //     // Tutup sidebar jika overlay diklik
    //     sidebarOverlay.addEventListener("click", () => {
    //         body.classList.remove("sidebar-mobile-open");
    //     });
    // }

    // --- Logika untuk Chart (hanya akan berjalan di halaman dashboard) ---
    // Cek jika elemen chart ada sebelum merendernya
    if (document.querySelector("#category-chart")) {
        // Pastikan ApexCharts tersedia dari CDN
        if (typeof ApexCharts !== "undefined") {
            const pieColors = [
                "#e74c3c",
                "#1abc9c",
                "#f1c40f",
                "#3498db",
                "#9b59b6",
            ];

            var optionsCategory = {
                series: [33.3, 33.3, 33.3],
                chart: { type: "pie", height: 320 },
                labels: [
                    "Employment",
                    "Plant Operation",
                    "Management of building",
                ],
                colors: [pieColors[0], pieColors[1], pieColors[2]],
                legend: { position: "bottom" },
            };
            new ApexCharts(
                document.querySelector("#category-chart"),
                optionsCategory
            ).render();

            var optionsType = {
                series: [33.3, 33.3, 33.3],
                chart: { type: "pie", height: 320 },
                labels: ["notification", "Report", "License"],
                colors: [pieColors[0], pieColors[3], pieColors[1]],
                legend: { position: "bottom" },
            };
            new ApexCharts(
                document.querySelector("#type-chart"),
                optionsType
            ).render();

            var optionsDepartment = {
                series: [66.7, 16.7, 16.7],
                chart: { type: "donut", height: 320 },
                labels: [
                    "PRODUCTION PREPARATION",
                    "MAINTENANCE",
                    "QUALITY ASSURANCE",
                ],
                colors: [pieColors[2], pieColors[1], pieColors[0]],
                legend: { position: "bottom" },
            };
            new ApexCharts(
                document.querySelector("#department-chart"),
                optionsDepartment
            ).render();

            var optionsLine = {
                series: [
                    {
                        name: "Docs Out",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0.1, 0.9, 1.0, 1.0],
                    },
                    {
                        name: "Docs Return",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    },
                ],
                chart: { height: 350, type: "line", toolbar: { show: false } },
                stroke: { curve: "smooth", width: 2 },
                xaxis: {
                    categories: [
                        "Mar-24",
                        "Apr-24",
                        "May-24",
                        "Jun-24",
                        "Jul-24",
                        "Aug-24",
                        "Sep-24",
                        "Nov-24",
                        "Dec-24",
                        "Jan-25",
                        "Feb-25",
                    ],
                },
                yaxis: { min: 0, max: 1.0 },
                legend: { position: "bottom" },
                colors: [pieColors[3], pieColors[4]],
            };
            new ApexCharts(
                document.querySelector("#docs-out-return-chart"),
                optionsLine
            ).render();
        }
    }

};

document.addEventListener("DOMContentLoaded", initAppEnhancements);
document.addEventListener("livewire:navigated", initAppEnhancements);
