const search = document.getElementById("search");
search.addEventListener("keyup", function (e) {
  if (e.which == "13") {
    event.preventDefault();
  }
});

async function getPanduanAll(search){
  BeforeSend("Loadachievment");
  try {
    const searchValue = '';
    const result      = await getPanduan(searchValue);
    updatePanduanUI(result)
  } catch (error) {
  }
  Complete("Loadachievment");
}

getPanduanAll();

search.addEventListener("keyup", async function () {
  BeforeSend("Loadachievment");
  try {
    const searchValue = this.value;
    const result      = await getPanduan(searchValue);
    updatePanduanUI(result)
  } catch (error) {
  }
  Complete("Loadachievment");
});

function getPanduan(searchValue) {
  if (searchValue === 0) {
    getPanduanAll();
  }else{
    return fetch("/api/searchpanduan", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ data: searchValue }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(response.statusText);
        }
        return response.json();
      })
      .then((response) => {
        if (response.Response === "False") {
          throw new Error(response.statusText);
        }
        return response;
      });
  }
}

function updatePanduanUI(data) {
  let divHtml = "";
  data.forEach((item) => {
    divHtml += showDivHtml(item);
  });

  const divHtmlContainer = document.getElementById("showData");
  divHtmlContainer.innerHTML = divHtml;
}

function showDivHtml(item) {
  return `
          <div class="mt-1">
          <a href="/user/panduan/${item.slug}" class="fa fa-arrow-right"> ${item.judul}</a>
          </div>
`;
}


function BeforeSend(idLoader) {
  $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
  $("#" + idLoader + "").addClass("d-none");
}