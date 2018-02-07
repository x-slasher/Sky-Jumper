<!doctype html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8" />
    <title>Game - Demo</title>
    <script type="text/javascript" src="js/phaser.js"></script>
    <style type="text/css">
        body {
            margin: 10px 15%;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<script type="text/javascript">

var game = new Phaser.Game(800, 600, Phaser.AUTO, '', { preload: preload, create: create, update: update,render:render });

function preload() {
    game.load.image('enemyBullet', 'assets/enemy-bullet.png');
    game.load.image('life', 'assets/life.png');
    game.load.image('sky', 'assets/starfield.png');
    game.load.image('ledge', 'assets/ledge.png');
    game.load.image('ground', 'assets/platform.png');
    game.load.image('star', 'assets/star.png');
    game.load.spritesheet('dude', 'assets/dude2.png', 23.3, 35);
    game.load.image('shooter','assets/invader.png');
    game.load.spritesheet('kaboom', 'assets/explode.png', 128, 128);
    game.load.spritesheet('button','assets/button1.png',130,54);

   // game.load.tilemap('level1', 'assets/level1.json', null, Phaser.Tilemap.TILED_JSON);
}
var button;
var butttonText;
var player;
var lives;
var villain;
var platforms;
var cursors;
var ledge,ledge2,ledge3,ledge4,ledge5;
var sky;
var stars;
var score = 0;
var scoreText;
var statText;
var alive=1 ;
var enemyBullet;
var firingTimer = 0;
var dead = 0;
var resetButton;
var explosions;

function create() {

    

    
    //  We're going to be using physics, so enable the Arcade Physics system
    game.physics.startSystem(Phaser.Physics.ARCADE);

   

    //  A simple background for our game
    sky = game.add.tileSprite(0, 0,800,600, 'sky');
//    sky.fixedToCamera = true;

    //  The platforms group contains the ground and the 2 ledges we can jump on
    platforms = game.add.group();
    //  We will enable physics for any object that is created in this group
    platforms.enableBody = true;

    // Here we create the ground.
    var ground = platforms.create(0, game.world.height - 56, 'ground');

    //  Scale it to fit the width of the game (the original sprite is 400x32 in size)
    ground.scale.setTo(2, 2);

    //  This stops it from falling away when you jump on it
    ground.body.immovable = true;
    ground.body.checkCollision.up = true;
    ground.scale.setTo(2, 2); 

    ledge = game.add.group();
    ledge.enableBody = true;
    ledge = ledge.create(380, 450, 'ledge');    
    ledge.body.immovable = true;
    ledge.name = 'horizontal';
 
    //enable physics
    game.physics.arcade.enable(ledge); 
    ledge.body.collideWorldBounds = true;
    ledge.body.checkCollision.down = true;
    ledge.body.kinematic=true;
    ledge.leftbounds = ledge.body.x-340;  
    ledge.rightbounds = ledge.body.x + 256;   //move 256 pixel right
    ledge.velo=50; 

    ledge2 = game.add.group();
    ledge2.enableBody = true;
    ledge2 = ledge2.create(480, 350, 'ledge');    
    ledge2.body.immovable = true;
    ledge2.name = 'horizontal';
 
    //enable physics
    game.physics.arcade.enable(ledge2); 
    ledge2.body.collideWorldBounds = true;
    ledge2.body.checkCollision.down = true;
    ledge2.body.kinematic=true;
    ledge2.leftbounds = ledge2.body.y-340;  
    ledge2.rightbounds = ledge2.body.y + 256;   //move 256 pixel right
    ledge2.velo=30; 

    //ledge3
    ledge3 = game.add.group();
    ledge3.enableBody = true;
    ledge3 = ledge3.create(280, 250, 'ledge');    
    ledge3.body.immovable = true;
    ledge3.name = 'horizontal';
 
    //enable physics
    game.physics.arcade.enable(ledge3); 
    ledge3.body.collideWorldBounds = true;
    ledge3.body.checkCollision.down = true;
    ledge3.body.kinematic=true;
    ledge3.leftbounds = ledge3.body.x-240;  
    ledge3.rightbounds = ledge3.body.x + 256;   //move 256 pixel right
    ledge3.velo=60;
    //ledge 4

    ledge4 = game.add.group();
    ledge4.enableBody = true;
    ledge4 = ledge4.create(480, 150, 'ledge');    
    ledge4.body.immovable = true;
    ledge4.name = 'horizontal';
 
    //enable physics
    game.physics.arcade.enable(ledge4); 
    ledge4.body.collideWorldBounds = true;
    ledge4.body.checkCollision.down = true;
    ledge4.body.kinematic=true;
    ledge4.leftbounds = ledge4.body.x-470;  
    ledge4.rightbounds = ledge4.body.x + 156;   //move 256 pixel right
    ledge4.velo=70; 
    
    //ledge 5
    ledge5 = game.add.group();
    ledge5.enableBody = true;
    ledge5 = ledge5.create(180, 50, 'ledge');    
    ledge5.body.immovable = true;
    ledge5.name = 'horizontal';
 
    //enable physics
    game.physics.arcade.enable(ledge5); 
    ledge5.body.collideWorldBounds = true;
    ledge5.body.checkCollision.up = true;
    ledge5.body.checkCollision.down = false;
    ledge5.body.kinematic=true;
    ledge5.leftbounds = ledge5.body.x-170;  
    ledge5.rightbounds = ledge5.body.x + 450;   //move 256 pixel right
    ledge5.velo=60; 
  
   // ledge.resizeWorld();

//shooter
    villain = game.add.sprite(0,400,'shooter'); 
    villain.anchor.setTo(0.0, 0.0);
    game.physics.arcade.enable(villain);


// adding bullet
    enemyBullets = game.add.group();
    enemyBullets.enableBody = true;
    enemyBullets.physicsBodyType = Phaser.Physics.ARCADE;
   // enemyBullets.angle = 90;
    enemyBullets.createMultiple(3, 'enemyBullet');
    enemyBullets.setAll('anchor.x', 0.0);
    enemyBullets.setAll('anchor.y', 0.0);
    enemyBullets.setAll('outOfBoundsKill', true);
    enemyBullets.setAll('checkWorldBounds', true);


    // The player and its settings
    player = game.add.sprite(445, 390, 'dude'); 
    game.physics.arcade.enable(player);  
    player.body.gravity.y = 600;
    player.body.collideWorldBounds = true;
   //  walking left and right.
    player.animations.add('left', [0, 1, 2, 3], 10, true);
    player.animations.add('right', [5, 6, 7, 8], 10, true);

    game.camera.follow(player);
    //  Text
    statText = game.add.text(game.world.centerX,game.world.centerY,' ', { font: '44px Arial', fill: '#fff',align:'center' });
    statText.anchor.setTo(0.5, 0.5);
    statText.visible = false;
    //  Lives
    lives = game.add.group();
    game.add.text(game.world.width - 170, 10, 'Lives : ', { font: '24px Arial', fill: '#fff' });
    for (var i = 0; i < 3; i++) 
    {
        var life = lives.create(game.world.width - 90 + (30 * i), 60, 'life');
        life.anchor.setTo(0, 2.3);
        //ship.angle = 90;
        life.alpha = 1.3;
    }


    stars = game.add.group();
    stars.enableBody = true; 
    createStars();

   //  An explosion pool
    explosions = game.add.group();
    explosions.createMultiple(30, 'kaboom');
    explosions.forEach(setPlayer, this);
    //  The score
    
    scoreText = game.add.text(5, 5, 'Score: 0', { fontSize: '30px', fill: '#fff' });

    //  Our controls.
    cursors = game.input.keyboard.createCursorKeys();
    resetButton = game.input.keyboard.addKey(Phaser.Keyboard.SPACEBAR);
    button = game.add.button(game.world.centerX - 80, game.world.height-55, 'button', actionOnClick, this);
    butttonText = game.add.text(game.world.centerX - 60, game.world.height-40,'Level-2', { fontSize: '30px', fill: '#fff' });
    button.visible = false;
    butttonText.visible = false;
}
function actionOnClick () {
    game.paused = false;
    location.href="new1.php";

}
function update() {
    sky.tilePosition.y += 0.5;
    // ledge.body.position.y+=0.2;
    //  Collide the player and the stars with the platforms
    var hitPlatform = game.physics.arcade.collide(player, platforms);
    var hitLegde = game.physics.arcade.collide(player, ledge);
    var hitLegde2 = game.physics.arcade.collide(player, ledge2);
    var hitLegde3 = game.physics.arcade.collide(player, ledge3);
    var hitLegde4=game.physics.arcade.collide(player,ledge4);
    var hitLegde5=game.physics.arcade.collide(player,ledge5);


    //var hitLegde2 = game.physics.arcade.collide(player, ledge2);
    var starsBounce = game.physics.arcade.collide(stars, platforms);
   if(player.alive){
    //check if stars touches player
    var collectStars = game.physics.arcade.overlap(player, stars, collectStar, null, this);
    alive = stars.countLiving();
     if(alive==0){
        createStars();
     }
    
       //  Reset the players velocity (movement)
    player.body.velocity.x = 0;

    if (cursors.left.isDown)
    {
        //  Move to the left
        player.body.velocity.x = -150;
        player.animations.play('left'); 
    }
    else if (cursors.right.isDown)
    {
        //  Move to the right
        player.body.velocity.x = 150;
        player.animations.play('right');
    }
    else
    {
        //  Stand still
        player.animations.stop();

        player.frame = 4;
    }

    //  Allow the player to jump if they are touching the ground.
    if (cursors.up.isDown && player.body.touching.down && (hitPlatform || hitLegde ||  hitLegde2 || hitLegde3 || hitLegde4 ))
    {
        player.body.velocity.y = -350;

    }
    if(hitLegde ||  hitLegde2 ){
        dead = 0;
    }
    if(hitLegde5){
     //   game.paused = true;
        enemyBullets.callAll('kill');
        button.visible = true;
        butttonText.visible = true;
        statText.text=" Level 1 Complete";
        statText.visible = true;
        //game.input.onTap.addOnce(restart,this);
      //  game.input.onDown.addOnce(restart);
       // resetButton.onDown.addOnce(restart);
       // 
    }
    var live = lives.getFirstAlive();
    if(hitPlatform && live){
        dead++;
        hitPlatform = false;
        if(dead>1)
        {
            live.kill();
            dead = 0;

        }
        
    }
        
    //  if (jumpButton.isDown)
    // {
    //     player.body.velocity.y = -100;
    // }

    if(lives.countLiving()<1)
    {
        player.kill();
        enemyBullets.callAll('kill');

        statText.text=" Game Over \n press SPACEBAR to restart";
        statText.visible = true;
        //game.input.onTap.addOnce(restart,this);
        game.input.onDown.addOnce(restart);
        resetButton.onDown.addOnce(restart);
        game.paused = true;
    }
    if (game.time.now > firingTimer)
    {
        enemyFires();
    }
game.physics.arcade.overlap(enemyBullets, player, enemyHitsPlayer, null, this);
 movePlatforms(ledge);
 movePlatforms(ledge2);
 movePlatforms(ledge3);
 movePlatforms(ledge4);
 movePlatforms(ledge5);
}
}

function enemyFires(){
//  Grab the first bullet we can from the pool
      
    enemyBullet = enemyBullets.getFirstExists(false);
     //   var random=game.rnd.integerInRange(0,3);

        if (enemyBullet )
        {
            var shooter=villain;
            // And fire the bullet from this enemy
            enemyBullet.reset(shooter.body.x, shooter.body.y);
            enemyBullet.angle = 90;
            game.physics.arcade.moveToObject(enemyBullet,player,60);
            firingTimer = game.time.now + 1500;
        }
    
}
function enemyHitsPlayer (player,bullet) {
    
    bullet.kill();

   live = lives.getFirstAlive();

    if (live)
    {
        live.kill();
    }
    //  And create an explosion :)
    var explosion = explosions.getFirstExists(false);
    explosion.reset(player.body.x, player.body.y);
    explosion.play('kaboom', 30, false, true);

    // When the player dies
    if (lives.countLiving() < 1)
    {
        player.kill();
        enemyBullets.callAll('kill');
        statText.text=" Game Over \n press SPACEBAR to restart";
        statText.visible = true;
       // game.input.onTap.addOnce(restart,this);
       game.input.onDown.addOnce(restart);
        resetButton.onDown.addOnce(restart);
        game.paused = true;
    }

}

function setPlayer (player) {

    player.anchor.x = 0.5;
    player.anchor.y = 0.5;
    player.animations.add('kaboom');

}
function createStars(){

    for (var i = 0; i < 15; i++)
    {
        //  Create a star inside of the 'stars' group
        var star =stars.create(game.world.randomX, game.world.randomY-200, 'star');// stars.create(i * 60, 0, 'star');
        game.physics.arcade.enable(star);
        star.body.collideWorldBounds = false;
        star.checkWorldBounds= true;
        star.outOfBoundsKill = true;
        star.body.kinematic=true;
        star.leftbounds = star.body.x-256;  
        star.rightbounds = star.body.x + 256;   //move 256 pixel right
        star.velo=50;     
        star.body.sprite.velo *= -1;
        star.body.sprite.velo *= -1;  // reverse speed
    // star.body.checkCollision.down = false;
        if(i%2==0){
            star.body.gravity.x = Math.random()*5;
            star.body.bounce.x = (0.7 + Math.random() * 1.5)%6;
            if((i+i)%3==0){
                star.body.gravity.x = -Math.random()*7;
            star.body.bounce.x = (0.7 + Math.random() * 0.5)%7;
            }
        }
      // var star = game.add.sprite(game.world.randomX, game.world.randomY, 'star');
            //  Let gravity do its thing
              star.body.gravity.y = 40+(Math.random())%13;
                //  This just gives each star a slightly random bounce value
                star.body.bounce.y = (0.7 + Math.random() * 0.5)%3;
    }
}

function restart () {
     
  //    createStars();
//resume game state
    game.paused = false;
    
    lives.callAll('revive');
    stars.removeAll();
    //revives the player
    player.revive();
    //player.body.velocity.y= -350;
    player.position.x= ledge.position.x+30;
    player.position.y=390;
    dead = 0;
    score = 0
    scoreText.text = 'Score: ' + score;
    //hides the text
    statText.visible = false;
   // ledge.body.reset=true;

}

function  movePlatforms(ledge){
    if (ledge.body.sprite.name == 'horizontal'){   //check for the moving direction 
        if (ledge.body.x > ledge.body.sprite.rightbounds){  ledge.body.sprite.velo *= -1;} //if reached bounds reverse speed
        if (ledge.body.x < ledge.body.sprite.leftbounds) { ledge.body.sprite.velo *= -1;}  // reverse speed
        ledge.body.velocity.x = ledge.body.sprite.velo;
    } else if (ledge.body.sprite.name == 'vertical'){
        if (ledge.body.y > ledge.body.sprite.bottombounds){  ledge.body.sprite.velo *= -1;}
        if (ledge.body.y < ledge.body.sprite.topbounds) { ledge.body.sprite.velo *= -1;}
        ledge.body.velocity.y = ledge.body.sprite.velo;
    } 
}

function collectStar (player, star) {
    // Removes the star from the screen
    star.kill();
    //  Add and update the score
    score += 10;
       scoreText.text = 'Score: ' + score;
}

function render() {

  //  game.debug.cameraInfo(game.camera, 32, 32);

}

</script>


</body>
</html>