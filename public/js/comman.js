function toast_success(msg)
{
  Toastify({
    newWindow: true,
    text: msg,
    gravity:'top',
    position: 'right',
    className: "bg-success",
    stopOnFocus: true,
    offset: {
      x:  50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
      y: 10, // vertical axis - can be a number or a string indicating unity. eg: '2em'
    },
    duration: 3000,
    close:  true,
    
  }).showToast();
}

function toast_error(msg)
{
  Toastify({
    newWindow: true,
    text: msg,
    gravity:'top',
    position: 'right',
    className: "bg-error",
    stopOnFocus: true,
    offset: {
      x:  50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
      y: 10, // vertical axis - can be a number or a string indicating unity. eg: '2em'
    },
    duration: 3000,
    close:  true,
    
  }).showToast();
}
