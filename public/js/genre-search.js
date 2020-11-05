$(document).ready(function(){

    let suggestions = document.querySelector("#suggestions");
    let genreDropdown = document.querySelector("#genre-dropdown");
    let genreDropdownToggle = document.querySelector("#genre-dropdown-toggle");
    let url = document.querySelector('#genre-search').dataset.path;
    const minLetters = 2;

    $("#genre-search").keyup(function(){
        if ($(this).val().length >= minLetters) {
            $.ajax({
                type: "POST",
                url: url,
                data: "genre="+$(this).val(),
                success: function(data){
                    let genreData = JSON.parse(data.genres);
                    let hasChildren = suggestions.hasChildNodes();

                    if (hasChildren){
                        while (suggestions.firstChild) {
                            suggestions.removeChild(suggestions.firstChild);
                        }
                    }

                    genreDropdown.classList.add('show');
                    genreDropdownToggle.classList.add('active');

                    genreData.map((genre, index) => {
                        let select = document.createElement("select");
                        let newDiv = document.createElement("option");
                        newDiv.innerText = genre.name
                        newDiv.tabIndex = 0;
                        suggestions.append(newDiv);
                    });
                },
            });
        }
    });
});
