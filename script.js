// Выполняем AJAX запрос к серверу для получения списка игр
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var games = JSON.parse(this.responseText);
        var gameContainer = document.getElementById("game-container");

        // Создаем блоки для каждой игры
        games.forEach(function(game) {
            var gameItem = document.createElement("div");
            gameItem.classList.add("game-item");

            var img = document.createElement("img");
            img.src = "http://localhost/kurs/" + game.image_url; // Полный URL пути к изображению обложки игры
            img.alt = game.title; // Альтернативный текст для изображения
            gameItem.appendChild(img);

            var link = document.createElement("a");
            link.href = "game.html?game_id=" + game.game_id;
            link.textContent = game.title;
            gameItem.appendChild(link);

            gameContainer.appendChild(gameItem);
        });
    }
};
xhttp.open("GET", "get_games.php", true);
xhttp.send();
