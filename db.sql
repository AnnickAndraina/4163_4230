CREATE DATABASE food_design;
USE food_design;

CREATE TABLE plats (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    emoji VARCHAR(10),
    img VARCHAR(255),
    cat VARCHAR(50),
    time VARCHAR(20),
    cal VARCHAR(20),
    rating VARCHAR(10),
    description TEXT
);

CREATE TABLE categories (
    id INT PRIMARY KEY,
    name VARCHAR(50)
);


CREATE TABLE emojis (
    id INT PRIMARY KEY,
    symbol VARCHAR(10)
);


INSERT INTO plats (id, name, emoji, img, cat, time, cal, rating, description) VALUES
(1, "Ramen Tonkotsu", "🍜", "public/images/ramen.jpg", "Japonais", "45 min", "620 kcal", "4.8", "Bouillon de porc riche, nouilles fraîches, œuf mollet et chashu."),
(2, "Pizza Margherita", "🍕", "public/images/pizza.jpg", "Italien", "30 min", "540 kcal", "4.7", "Tomate San Marzano, mozzarella di bufala, basilic frais."),
(3, "Tacos al Pastor", "🌮", "public/images/tacos.jpg", "Mexicain", "20 min", "480 kcal", "4.6", "Porc mariné aux épices, ananas, coriandre et salsa verde."),
(4, "Pad Thaï", "🍝", "public/images/padthai.jpg", "Thaïlandais", "25 min", "550 kcal", "4.5", "Nouilles de riz sautées, crevettes, cacahuètes et citron vert."),
(5, "Burger Smash", "🍔", "public/images/burger.jpg", "Américain", "15 min", "750 kcal", "4.9", "Double galette beurrée, cheddar fondu, pickles maison."),
(6, "Sushi Omakase", "🍣", "public/images/sushi.jpg", "Japonais", "60 min", "420 kcal", "5.0", "Sélection du chef : thon, saumon, oursin et bar de ligne."),
(7, "Shakshuka", "🍳", "public/images/shakshuka.jpg", "Oriental", "20 min", "390 kcal", "4.4", "Œufs pochés dans une sauce tomate épicée aux poivrons."),
(8, "Crêpe Suzette", "🥞", "public/images/crepes.jpg", "Français", "15 min", "310 kcal", "4.6", "Crêpes au beurre d'agrumes flambées au Grand Marnier."),
(9, "Biryani d'agneau", "🍚", "public/images/biryani.jpg", "Indien", "90 min", "680 kcal", "4.8", "Riz basmati parfumé, agneau tendre, safran et raïta."),
(10, "Poke Bowl Saumon", "🥗", "public/images/pokebowl.jpg", "Hawaïen", "10 min", "490 kcal", "4.7", "Riz sushi, saumon frais, avocat, edamame et sauce ponzu."),
(11, "Couscous Royal", "🍲", "public/images/couscous.jpg", "Maghrébin", "75 min", "720 kcal", "4.9", "Semoule fine, merguez, poulet, légumes et bouillon parfumé."),
(12, "Tiramisu", "🍮", "public/images/tiramisu.jpg", "Dessert", "20 min", "380 kcal", "4.8", "Mascarpone aérien, biscuits imbibés d'espresso et cacao.")


INSERT INTO categories (id, name) VALUES
(1, 'Français'),
(2, 'Italien'),
(3, 'Japonais'),
(4, 'Mexicain'),
(5, 'Indien'),
(6, 'Thaïlandais'),
(7, 'Américain'),
(8, 'Oriental'),
(9, 'Maghrébin'),
(10, 'Hawaïen'),
(11, 'Dessert');


INSERT INTO emojis (id, symbol) VALUES
(1, '🍕'),
(2, '🍔'),
(3, '🌮'),
(4, '🌯'),
(5, '🍜'),
(6, '🍝'),
(7, '🍣'),
(8, '🍱'),
(9, '🍛'),
(10, '🍲'),
(11, '🥘'),
(12, '🍚'),
(13, '🥗'),
(14, '🍳'),
(15, '🥞'),
(16, '🧆'),
(17, '🥙'),
(18, '🫔'),
(19, '🍢'),
(20, '🍱'),
(21, '🥩'),
(22, '🍗'),
(23, '🥚'),
(24, '🧀'),
(25, '🥓'),
(26, '🌭'),
(27, '🥪'),
(28, '🫕'),
(29, '🍮'),
(30, '🧁'),
(31, '🎂'),
(32, '🍰'),
(33, '🍩'),
(34, '🍪'),
(35, '🍫'),
(36, '🍦'),
(37, '🍧'),
(38, '🍨'),
(39, '🥧'),
(40, '🍡'),
(41, '🍷'),
(42, '🥂'),
(43, '🍺'),
(44, '🧋'),
(45, '🥤'),
(46, '☕'),
(47, '🍵'),
(48, '🥛'),
(49, '🍹'),
(50, '🧃');





