// TODO
$(document).ready(function(){

    let genreDropdown = document.querySelector("#genre-dropdown");
    let genreDropdownToggle = document.querySelector("#genre-dropdown-toggle");
    let url = document.querySelector('#genre-search').dataset.path;
    let genreArray = [];

    $("#genre-search").keyup(function(){
        if ($(this).val().length > 1) {
            $.ajax({
                type: "POST",
                url: url,
                data: "genre="+$(this).val(),
                success: function(data){
                    console.log(data.genres);
                    genreDropdown.classList.add('show');
                    genreDropdownToggle.classList.add('active');

                    data.genres.forEach(element => genreArray.push(element.name, "<br>"));

                    $("#suggestions").html(genreArray);
                    genreArray = [];
                },
            });
        }
    });
});
