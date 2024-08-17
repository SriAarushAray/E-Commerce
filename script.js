document
  .getElementById("search-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    var query = document.getElementById("query").value;
    searchProducts(query);
  });

function searchProducts(query) {
  var results = [
    {
      name: "Shoes",
      url: "shoes.html",
      description: "High-quality shoes",
    },
    {
      name: "Furniture",
      url: "furniture.html",
      description: "Stylish furniture",
    },
    {
      name: "Cosmetics",
      url: "cosmetics.html",
      description: "Beauty products",
    },
    { name: "Books", url: "books.html", description: "Best-selling books" },
    {
      name: "Electronics",
      url: "electronics.html",
      description: "Latest tech",
    },
  ];

  displayResults(results);
}

function displayResults(results) {
  var html = "";
  for (var i = 0; i < results.length; i++) {
    html += "<div>";
    html +=
      '<h2><a href="' + results[i].url + '">' + results[i].name + "</a></h2>";
    html += "<p>" + results[i].description + "</p>";
    html += "</div>";
  }
  document.getElementById("results").innerHTML = html;
}
