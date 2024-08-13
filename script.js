// Get the modal element
const popup = document.getElementById('popup');

// Get the button that opens the popup
const popupBtn = document.getElementById('popupBtn');

// Get the <span> element that closes the popup
const closeBtn = document.querySelector('.close');

// Open the popup when the user clicks the button
popupBtn.onclick = function() {
    popup.style.display = 'block';
}

// Close the popup when the user clicks on <span> (x)
closeBtn.onclick = function() {
    popup.style.display = 'none';
}

// Close the popup when the user clicks outside of it
window.onclick = function(event) {
    if (event.target == popup) {
        popup.style.display = 'none';
    }
}

// Open the specific tab (Login or Signup)
function openTab(evt, tabName) {
    // Hide all tabcontent elements
    const tabcontent = document.querySelectorAll('.tabcontent');
    tabcontent.forEach(content => content.classList.remove('active'));

    // Remove the "active" class from all tablinks
    const tablinks = document.querySelectorAll('.tablinks');
    tablinks.forEach(link => link.classList.remove('active'));

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}

// Set the default tab to open
document.getElementById('defaultTab').click();
