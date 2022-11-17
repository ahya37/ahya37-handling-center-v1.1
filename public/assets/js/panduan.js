$(function () {
    $("#tablePlace").DataTable({
      processing: true,
      pageLength:200,
      language: {
        processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
      },
      serverSide: true,
      ordering: true,
      ajax: {
        url: "/panduan/listdata",
      },
      columns: [
        { data: "id", name: "id" },
        { data: "judul", name: "judul" },
        {
          data: "action",
          name: "action",
          orderable: false,
          searchable: false,
        },
      ],
      order: [[1, "asc"]],
      columnDefs: [
        {
          targets: [0],
          visible: false,
        },
      ],
    });
  });

  function onDelete(data) {

    const id   = data.id;
    const name = data.value;

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
      title: `Yakin hapus : ${name}?`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Hapus",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/umrah/tag/orange/delete`,
          method: "POST",
          cache: false,
          data: {
            id: id,
            _token: CSRF_TOKEN,
          },
          success: function (data) {            
            Swal.fire({
              position: "center",
              icon: "success",
              title: `${data.data.message}`,
              showConfirmButton: false,
              width: 500,
              timer: 900,
            });
            window.location.reload();
          },
          error: function (error) {
            Swal.fire({
              position: "center",
              icon: "danger",
              title: `Gagal hapus`,
              showConfirmButton: false,
              width: 500,
              timer: 900,
            });
          },
        });
      }
    });
  }