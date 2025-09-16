document.addEventListener('DOMContentLoaded', function() {

    const helpButtons = document.querySelectorAll('.helpBtn');
    let openPanel = null;

    helpButtons.forEach(button => {
        const postId = button.getAttribute('data-post');
        const helpPanel = document.getElementById(`helpPanel-${postId}`);
        button.addEventListener('click', function(event) {
            event.stopPropagation();

            if (openPanel && openPanel !== helpPanel) {
                openPanel.classList.remove('open');
            }
            helpPanel.classList.toggle('open');
            openPanel = helpPanel.classList.contains('open') ? helpPanel : null;
        });
    });
    document.addEventListener('click', function(event) {
        if (openPanel && !openPanel.contains(event.target)) {
            openPanel.classList.remove('open');
            openPanel = null;
        }
    });
});