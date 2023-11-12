
$(document).ready(function () {
  $('#dataTable').DataTable(); // ID From dataTable 
  $('#dataTableHover').DataTable(); // ID From dataTable with Hover
});

setTimeout(function(){
  removeMessage(window.location.href);
} , 1500);  


function removeMessage(url) {
  // Select the element with ID 'msg'
  var MsgElement = document.querySelector('#msg');
  // Check if the element exists
  if (MsgElement) {
    // If the element exists, remove it from the DOM
    MsgElement.remove();
    var url = window.location.href;
    var cleanUrl = url.split("?")[0]; // Get the URL without the query parameters
    window.location.href = cleanUrl; // Redirect to the clean URL
  }
}

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}

function confirmDeleteAll(option) {
  // Create the dialog container
  var dialog = document.createElement('div');
  dialog.className = 'confirm-dialog';

  // Create the dialog elements
  var heading = document.createElement('h3');
  heading.textContent = 'Confirmation';
  dialog.appendChild(heading);

  var message = document.createElement('p');
  message.textContent = 'Are you sure you want to delete all records?';
  dialog.appendChild(message);

  var buttons = document.createElement('div');
  buttons.className = 'buttons';

  var confirmBtn = document.createElement('button');
  confirmBtn.textContent = 'OK';
  confirmBtn.addEventListener('click', function() {
    option = option.trim();
    // Redirect to the delete page with the confirmation parameter
    window.location.href = 'DeleteAll.php?action=deleteAll&option=' + option;
  });
  buttons.appendChild(confirmBtn);

  var cancelBtn = document.createElement('button');
  cancelBtn.textContent = 'Cancel';
  cancelBtn.className = 'cancel';
  cancelBtn.addEventListener('click', function() {
    // Close the dialog without performing the delete operation
    document.body.removeChild(dialog);
  });
  buttons.appendChild(cancelBtn);

  dialog.appendChild(buttons);

  // Add the dialog to the document body
  document.body.appendChild(dialog);

  // Prevent the default action of the link (e.g., navigating to a new page)
  return false;
}

function downloadFile(content, filename) {
  const blob = new Blob([content], { type: 'text/plain' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
 }

function confirmDelete(id) {
  var dialog = document.createElement('div');
  dialog.className = 'confirm-dialog';
  
  var heading = document.createElement('h3');
  heading.textContent = 'Confirmation';
  dialog.appendChild(heading);
  
  var message = document.createElement('p');
  message.textContent = 'Are you sure you want to delete this record?';
  dialog.appendChild(message);
  
  var buttons = document.createElement('div');
  buttons.className = 'buttons';
  
  var confirmBtn = document.createElement('button');
  confirmBtn.textContent = 'OK';
  confirmBtn.addEventListener('click', function() {
      // Redirect to the delete page with the confirmation parameter
      window.location.href = '?action=delete&Id=' + id + '&confirm=yes';
  });
  buttons.appendChild(confirmBtn);
  
  var cancelBtn = document.createElement('button');
  cancelBtn.textContent = 'Cancel';
  cancelBtn.className = 'cancel';
  cancelBtn.addEventListener('click', function() {
      // Close the dialog without performing the delete operation
      document.body.removeChild(dialog);
  });
  buttons.appendChild(cancelBtn);
  
  dialog.appendChild(buttons);
  
  document.body.appendChild(dialog);
  
  return false; // Prevent the default action of the delete link
}

(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery); // End of use strict

// Modal Javascript

$(document).ready(function () {
  $("#myBtn").click(function () {
    $('.modal').modal('show');
  });

  $("#modalLong").click(function () {
    $('.modal').modal('show');
  });

  $("#modalScroll").click(function () {
    $('.modal').modal('show');
  });

  $('#modalCenter').click(function () {
    $('.modal').modal('show');
  });
});

// Popover Javascript

$(function () {
  $('[data-toggle="popover"]').popover()
});
$('.popover-dismiss').popover({
  trigger: 'focus'
});


// Version in Sidebar

var version = document.getElementById('version-ruangadmin');

version.innerHTML = "Version 1.0.1";