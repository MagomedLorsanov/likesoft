$(document).ready(function () {
    $.ajax({
      url: "./src/get_content.php",
      type: "GET",
      data: { path: "../datafiles" },
      dataType: "json",
      success: (res) => {
        appendFileList(res);
      },
    });
  });