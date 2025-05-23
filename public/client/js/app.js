const toggleButton = document.getElementById('toggle-btn')
const sidebar = document.getElementById('sidebar')

function toggleSidebar() {
    sidebar.classList.toggle('close')
    toggleButton.classList.toggle('rotate')

    closeAllSubMenus()
}

function toggleSubMenu(button) {

    if (!button.nextElementSibling.classList.contains('show')) {
        closeAllSubMenus()
    }

    button.nextElementSibling.classList.toggle('show')
    button.classList.toggle('rotate')

    if (sidebar.classList.contains('close')) {
        sidebar.classList.toggle('close')
        toggleButton.classList.toggle('rotate')
    }
}

function closeAllSubMenus() {
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
        ul.classList.remove('show')
        ul.previousElementSibling.classList.remove('rotate')
    })
}


// extra js for effects
function openDeleteModal(action) {
    let modal = document.getElementById('deleteModal');
    let modalContent = modal.children[0];

    document.getElementById('deleteForm').setAttribute('action', action);

    // Show modal with fade-in effect
    modal.classList.remove('invisible', 'opacity-0');
    modal.classList.add('opacity-100');

    // Scale up content
    modalContent.classList.remove('scale-95');
    modalContent.classList.add('scale-100');
}

function closeDeleteModal() {
    let modal = document.getElementById('deleteModal');
    let modalContent = modal.children[0];

    // Fade-out effect
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');

    // Scale down content
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');

    // Hide modal completely after transition
    setTimeout(() => {
        modal.classList.add('invisible');
    }, 300);
}