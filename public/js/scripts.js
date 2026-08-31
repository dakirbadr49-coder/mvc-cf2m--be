/*!
* Start Bootstrap - Blog Post v5.0.8 (https://startbootstrap.com/template/blog-post)
* Copyright 2013-2022 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-blog-post/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project

// Mode jour / nuit
(function () {
    var STORAGE_KEY = "theme";

    function getStoredTheme() {
        try {
            return localStorage.getItem(STORAGE_KEY);
        } catch (e) {
            return null;
        }
    }

    function storeTheme(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {}
    }

    function preferredTheme() {
        return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute("data-theme", theme);
        document.querySelectorAll(".theme-toggle").forEach(function (btn) {
            btn.textContent = theme === "dark" ? "☀️" : "🌙";
        });
    }

    var currentTheme = getStoredTheme() || preferredTheme();
    applyTheme(currentTheme);

    document.addEventListener("DOMContentLoaded", function () {
        applyTheme(currentTheme);
        document.querySelectorAll(".theme-toggle").forEach(function (btn) {
            btn.addEventListener("click", function () {
                currentTheme = currentTheme === "dark" ? "light" : "dark";
                storeTheme(currentTheme);
                applyTheme(currentTheme);
            });
        });
    });
})();