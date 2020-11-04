// TODO
$(document).ready(function(){

    let genreDropdown = document.querySelector("#genre-dropdown");
    let genreDropdownToggle = document.querySelector("#genre-dropdown-toggle");
    let url = document.querySelector('#genre-search').dataset.path;
    const minLetters = 2;

    $("#genre-search").keyup(function(){
        if ($(this).val().length > minLetters) {
            $.ajax({
                type: "POST",
                url: url,
                data: "genre="+$(this).val(),
                success: function(data){
                    let suggestions = document.querySelector("#suggestions");
                    let hasChildren = suggestions.hasChildNodes();

                    if (hasChildren){
                        while (suggestions.firstChild) {
                            suggestions.removeChild(suggestions.firstChild);
                        }
                    }

                    genreDropdown.classList.add('show');
                    genreDropdownToggle.classList.add('active');

                    data.genres.map((genre, index) => {
                        let newDiv = document.createElement("div");
                        newDiv.innerText = genre.name
                        newDiv.tabIndex = index.toString();
                        suggestions.append(newDiv);
                    });
                },
            });
        }
    });
});
