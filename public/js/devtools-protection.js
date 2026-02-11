/**
 * 🔒 COMPLETE DEVELOPER TOOLS PROTECTION
 * Detects and blocks all DevTools access attempts
 * Auto-logout on detection
 */

(function () {
    "use strict";

    // Configuration
    const CONFIG = {
        LOGOUT_URL: "/logout", // Change to your logout URL
        REDIRECT_URL: "/login", // Redirect after logout
        CHECK_INTERVAL: 500, // Check every 500ms
        WARNING_MESSAGE:
            "⚠️ Developer tools detected! Logging out for security...",
        BLOCK_RIGHT_CLICK: true,
        BLOCK_KEYBOARD_SHORTCUTS: true,
        BLOCK_CONSOLE: true,
    };

    let devtoolsOpen = false;
    let logoutInProgress = false;

    // 🔥 Method 1: Console size detection (most reliable)
    function detectDevToolsByConsole() {
        const threshold = 160;
        const widthThreshold =
            window.outerWidth - window.innerWidth > threshold;
        const heightThreshold =
            window.outerHeight - window.innerHeight > threshold;
        return widthThreshold || heightThreshold;
    }

    // 🔥 Method 2: debugger statement detection
    function detectDevToolsByDebugger() {
        const start = new Date();
        debugger; // This will pause if DevTools is open
        const end = new Date();
        return end - start > 100; // If paused, time difference will be significant
    }

    // 🔥 Method 3: toString override detection
    function detectDevToolsByToString() {
        let devtoolsDetected = false;
        const element = new Image();

        Object.defineProperty(element, "id", {
            get: function () {
                devtoolsDetected = true;
                return "detected";
            },
        });

        console.log("%c", element);
        return devtoolsDetected;
    }

    // 🔥 Method 4: Performance timing detection
    function detectDevToolsByPerformance() {
        const start = performance.now();

        // Create fake object that will be inspected
        const obj = {};
        console.profile();
        console.profileEnd();

        const end = performance.now();
        return end - start > 5;
    }

    // 🔥 Method 5: Chrome-specific detection
    function detectChromeDevTools() {
        const widthThreshold = window.outerWidth - window.innerWidth > 160;
        const heightThreshold = window.outerHeight - window.innerHeight > 160;
        const orientation = widthThreshold ? "vertical" : "horizontal";

        if (
            !(heightThreshold && widthThreshold) &&
            ((window.Firebug &&
                window.Firebug.chrome &&
                window.Firebug.chrome.isInitialized) ||
                widthThreshold ||
                heightThreshold)
        ) {
            return true;
        }
        return false;
    }

    // 🚨 Main detection function
    function checkDevTools() {
        const methods = [detectDevToolsByConsole(), detectChromeDevTools()];

        const detected = methods.some((result) => result === true);

        if (detected && !devtoolsOpen) {
            devtoolsOpen = true;
            handleDevToolsDetection();
        } else if (!detected && devtoolsOpen) {
            devtoolsOpen = false;
        }
    }

    // 🔴 Handle DevTools detection
    function handleDevToolsDetection() {
        if (logoutInProgress) return;

        logoutInProgress = true;

        // Show warning
        alert(CONFIG.WARNING_MESSAGE);

        // Clear all sensitive data
        clearAllData();

        // Perform logout
        performLogout();
    }

    // 🗑️ Clear all data
    function clearAllData() {
        try {
            // Clear localStorage
            localStorage.clear();

            // Clear sessionStorage
            sessionStorage.clear();

            // Clear cookies
            document.cookie.split(";").forEach(function (c) {
                document.cookie = c
                    .replace(/^ +/, "")
                    .replace(
                        /=.*/,
                        "=;expires=" + new Date().toUTCString() + ";path=/",
                    );
            });

            // Clear IndexedDB
            if (window.indexedDB) {
                indexedDB.databases().then((databases) => {
                    databases.forEach((db) => {
                        indexedDB.deleteDatabase(db.name);
                    });
                });
            }

            console.log("All data cleared");
        } catch (e) {
            console.error("Error clearing data:", e);
        }
    }

    // 🚪 Perform logout
    function performLogout() {
        // Method 1: Send logout request
        fetch(CONFIG.LOGOUT_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify({ reason: "devtools_detected" }),
        })
            .then(() => {
                // Redirect after logout
                window.location.href = CONFIG.REDIRECT_URL;
            })
            .catch(() => {
                // Force redirect even if logout fails
                window.location.href = CONFIG.REDIRECT_URL;
            });
    }

    // 🚫 Block right-click
    if (CONFIG.BLOCK_RIGHT_CLICK) {
        document.addEventListener("contextmenu", function (e) {
            e.preventDefault();
            console.log("Right-click is disabled for security");
            return false;
        });
    }

    // 🚫 Block keyboard shortcuts
    if (CONFIG.BLOCK_KEYBOARD_SHORTCUTS) {
        document.addEventListener("keydown", function (e) {
            // F12
            if (e.keyCode === 123) {
                e.preventDefault();
                handleDevToolsDetection();
                return false;
            }

            // Ctrl+Shift+I (Windows/Linux)
            if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
                e.preventDefault();
                handleDevToolsDetection();
                return false;
            }

            // Ctrl+Shift+J (Console)
            if (e.ctrlKey && e.shiftKey && e.keyCode === 74) {
                e.preventDefault();
                handleDevToolsDetection();
                return false;
            }

            // Ctrl+Shift+C (Inspect)
            if (e.ctrlKey && e.shiftKey && e.keyCode === 67) {
                e.preventDefault();
                handleDevToolsDetection();
                return false;
            }

            // Ctrl+U (View source)
            if (e.ctrlKey && e.keyCode === 85) {
                e.preventDefault();
                return false;
            }

            // Cmd+Option+I (Mac)
            if (e.metaKey && e.altKey && e.keyCode === 73) {
                e.preventDefault();
                handleDevToolsDetection();
                return false;
            }

            // Cmd+Option+J (Mac Console)
            if (e.metaKey && e.altKey && e.keyCode === 74) {
                e.preventDefault();
                handleDevToolsDetection();
                return false;
            }

            // Cmd+Option+C (Mac Inspect)
            if (e.metaKey && e.altKey && e.keyCode === 67) {
                e.preventDefault();
                handleDevToolsDetection();
                return false;
            }
        });
    }

    // 🚫 Block console methods
    if (CONFIG.BLOCK_CONSOLE) {
        // Disable console in production
        if (
            window.location.hostname !== "localhost" &&
            window.location.hostname !== "127.0.0.1"
        ) {
            const noop = function () {};
            const methods = [
                "log",
                "debug",
                "info",
                "warn",
                "error",
                "table",
                "trace",
                "dir",
                "dirxml",
                "group",
                "groupCollapsed",
                "groupEnd",
                "clear",
                "count",
                "countReset",
                "assert",
                "profile",
                "profileEnd",
                "time",
                "timeLog",
                "timeEnd",
                "timeStamp",
            ];

            methods.forEach((method) => {
                console[method] = noop;
            });
        }
    }

    // 🔄 Continuous monitoring with debugger
    function continuousDebuggerCheck() {
        setInterval(function () {
            const before = new Date().getTime();
            debugger;
            const after = new Date().getTime();

            if (after - before > 100) {
                handleDevToolsDetection();
            }
        }, CONFIG.CHECK_INTERVAL);
    }

    // 🔍 Element inspection detection
    function detectElementInspection() {
        const element = document.createElement("div");
        element.style.display = "none";

        Object.defineProperty(element, "id", {
            get: function () {
                handleDevToolsDetection();
                return "";
            },
        });

        setInterval(() => {
            console.log(element);
            console.clear();
        }, 1000);
    }

    // 🛡️ Advanced protection using Proxy
    function setupProxyProtection() {
        if (typeof Proxy !== "undefined") {
            const handler = {
                get: function (target, prop) {
                    if (prop === "devtools") {
                        handleDevToolsDetection();
                    }
                    return target[prop];
                },
            };

            window.console = new Proxy(console, handler);
        }
    }

    // 🚀 Initialize all protections
    function init() {
        console.log("🔒 DevTools Protection Active");

        // Start continuous checking
        setInterval(checkDevTools, CONFIG.CHECK_INTERVAL);

        // Start debugger check
        continuousDebuggerCheck();

        // Setup element inspection detection
        detectElementInspection();

        // Setup proxy protection
        setupProxyProtection();

        // Monitor visibility change (when DevTools is opened, page visibility might change)
        document.addEventListener("visibilitychange", function () {
            if (document.hidden) {
                checkDevTools();
            }
        });

        // Monitor window resize (DevTools opening changes window size)
        window.addEventListener("resize", function () {
            checkDevTools();
        });

        // Monitor orientation change
        window.addEventListener("orientationchange", function () {
            checkDevTools();
        });
    }

    // 🎬 Start protection when DOM is ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }

    // 🔐 Prevent script removal
    Object.freeze(init);
    Object.freeze(checkDevTools);
    Object.freeze(handleDevToolsDetection);
})();
