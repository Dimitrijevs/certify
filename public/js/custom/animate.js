const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        console.log(entry);

        if (entry.isIntersecting) {
            entry.target.classList.add('animate-visible');
        }
    });
});

const hiddenElements = document.querySelectorAll('.animate-hidden');
hiddenElements.forEach((element) => {
    observer.observe(element);
});