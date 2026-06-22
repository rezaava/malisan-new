window.showSuccess = function (message) {
    Swal.fire({
        title: message,
        icon: "success",
        timer: 5000,
        showConfirmButton: false,
        toast: true,
        position: "top-end",
        customClass: {
            popup: "swal-small",
            title: "swal-title-small",
        },
    });
}

window.showError = function (message) {
    Swal.fire({
        title: message,
        icon: "error",
        timer: 7000,
        showConfirmButton: false,
        toast: true,
        position: "top-end",
        customClass: {
            popup: "swal-small",
            title: "swal-title-small",
        },
    });
}

window.showConfirm = function (title, text = "", onConfirm = null) {
    Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "بله",
        cancelButtonText: "خیر",
        reverseButtons: true,
        customClass: {
            popup: "swal-small",
            title: "swal-title-small",
            htmlContainer: "swal-text-small",
            confirmButton: "btn btn-danger px-4",
            cancelButton: "btn btn-secondary px-4",
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === "function") {
            onConfirm();
        }
    });
};
