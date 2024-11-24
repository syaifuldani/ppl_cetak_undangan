$(document).ready(function () {
    // Live search untuk navbar
    $('#navbarSearchBox').on('keyup', function () {
        var query = $(this).val(); // Ambil input pencarian navbar
        if (query.length > 0) {
            $.ajax({
                url: '', // Mengirim ke halaman yang sama
                method: 'POST',
                data: { query: query },
                success: function (response) {
                    $('#navbarSearchResults').html(response).fadeIn();
                },
            });
        } else {
            $('#navbarSearchResults').empty().fadeOut();
        }
    });

    // Live search untuk konten index
    $('#contentSearchBox').on('keyup', function () {
        var query = $(this).val(); // Ambil input pencarian konten index
        if (query.length > 0) {
            $.ajax({
                url: '', // Mengirim ke halaman yang sama
                method: 'POST',
                data: { query: query },
                success: function (response) {
                    $('#contentSearchResults').html(response).fadeIn();
                },
            });
        } else {
            $('#contentSearchResults').empty().fadeOut();
        }
    });

    // Sembunyikan hasil pencarian jika klik di luar
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#navbarSearchBox, #navbarSearchResults').length) {
            $('#navbarSearchResults').fadeOut();
        }
        if (!$(e.target).closest('#contentSearchBox, #contentSearchResults').length) {
            $('#contentSearchResults').fadeOut();
        }
    });
});
