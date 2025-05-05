document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".fade-in");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, { threshold: 0.1 });

    elements.forEach(element => observer.observe(element));

    // MutationObserver per monitorare i cambiamenti nel DOM
    const mutationObserver = new MutationObserver((mutationsList) => {
        mutationsList.forEach(mutation => {
            if (mutation.type === "childList") {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && node.classList.contains("fade-in")) {
                        observer.observe(node);
                    }
                });
            }
        });
    });

    // Osserva il body per i cambiamenti
    mutationObserver.observe(document.body, { childList: true, subtree: true });
});