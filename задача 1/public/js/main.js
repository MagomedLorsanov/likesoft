$(document).ready(function () {
  $.ajax({
    url: "./src/file_search.php",
    type: "GET",
    data: { path: "../datafiles" },
    dataType: "json",
    success: (res) => {
      appendFileList(res);
    },
  });
});

function appendFileList(res) {
  let filesList = $(".files-list");
  filesList.empty();
  
  $.each(res, function (key, file_name) {
    filesList.append(`
        <div class="file d-flex mx-5">
        <div class="file-count mx-2">${key + 1}</div>
        <div class="file-name">${file_name}</div>
    `);
  });
}
