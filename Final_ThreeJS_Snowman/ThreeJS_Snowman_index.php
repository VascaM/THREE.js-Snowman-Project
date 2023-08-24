<html>
	<head>
		<title>My own three.js code</title>
		<style>
			body {margin:0;}
			canvas {width:100%; height:100%}
			
			#instructions {
			position: absolute;
			color: #000000;
			top: 0;
			padding-top: 20px;
			font-family: sans-serif;
			width: 100%;
			text-align: center;
			pointer-events: none;
  			font-size: 20px;
				text-shadow:  0 0 10px #FFFFFF; 
			}
		</style>
	</head>
	<body>
		<div id="instructions">
				Click on the snowman to let him see the snow.
		</div>
		
		<script src="js/three.js"></script>
		<script src="js/OrbitControls.js"></script>
		<script src="js/GLTFLoader.js"></script>
		
		<script> //THREE.JS SCRIPT
		
			var deltaTime
            var particleSystem; 
            var action;
            var clock1 = new THREE.Clock();
            var clock2 = new THREE.Clock();
            var targetPositionX1 = -1;
            var targetPositionX2 = -1.33;
            var targetPositionX3 = -2.1;
            var targetPositionX4 = -5.5;
            var targetPositionX5 = 1.30;
            var scene = new THREE.Scene();
            
            //CAMERA
            var camera = new THREE.PerspectiveCamera( 75, window.innerWidth/window.innerHeight, 0.1, 1000 );
            camera.position.set( 0, 0, 3.3 );
            
            //RENDERER
            var renderer = new THREE.WebGLRenderer();
            renderer.setSize( window.innerWidth, window.innerHeight );
            document.body.appendChild( renderer.domElement );
            renderer.setClearColor(0x606060);
            
            //PLANE1
            var planeTex1 = new THREE.TextureLoader().load('https://raw.githubusercontent.com/VascaM//THREE.js-Snowman-Project/8e186e085e383fbfb70e258d6eca362bdd4a1c87/Assets/Background_Snow/Background1_Sky.png');
            var planeMat1 = new THREE.MeshBasicMaterial( { map: planeTex1 } );
            var geometry1 = new THREE.PlaneGeometry( 15, 5, 0 );
            plane1 = new THREE.Mesh( geometry1, planeMat1 );
            plane1.position.x = 0.5;
            plane1.position.z = (0.1);
            scene.add( plane1 );
            
            //PLANE2
            var planeTex2 = new THREE.TextureLoader().load('https://raw.githubusercontent.com/VascaM//THREE.js-Snowman-Project/8e186e085e383fbfb70e258d6eca362bdd4a1c87/Assets/Background_Snow/Background2_Rocks.png');
            var planeMat2 = new THREE.MeshBasicMaterial( { map: planeTex2,transparent: true } );
            var geometry2 = new THREE.PlaneGeometry( 15, 5, 0 );
            plane2 = new THREE.Mesh( geometry2, planeMat2 );
            plane2.position.x = 1.9;
            plane2.position.y = 0.1;
            plane2.position.z = (0.2);
            scene.add( plane2 );
            
            //PLANE3
            var planeTex3 = new THREE.TextureLoader().load('https://raw.githubusercontent.com/VascaM//THREE.js-Snowman-Project/8e186e085e383fbfb70e258d6eca362bdd4a1c87/Assets/Background_Snow/Background3_Ground.png');
            var planeMat3 = new THREE.MeshBasicMaterial( { map: planeTex3,transparent: true } );
            var geometry3 = new THREE.PlaneGeometry( 15, 5, 0 );
            plane3 = new THREE.Mesh( geometry3, planeMat3 );
            plane3.position.x = 3;
            plane3.position.y = 0.1;
            plane3.position.z = (0.3);
            scene.add( plane3 );
            
            //PLANE4
            var planeTex4 = new THREE.TextureLoader().load('https://raw.githubusercontent.com/VascaM//THREE.js-Snowman-Project/8e186e085e383fbfb70e258d6eca362bdd4a1c87/Assets/Background_Snow/Background4_Tree.png');
            var planeMat4 = new THREE.MeshBasicMaterial( { map: planeTex4,transparent: true } );
            var geometry4 = new THREE.PlaneGeometry( 4.5, 5, 0 );
            plane4 = new THREE.Mesh( geometry4, planeMat4 );
            plane4.position.x = -0.5;
            plane4.position.z = (2);
            scene.add( plane4 );

            //LIGHT
            var ambientLight = new THREE.AmbientLight(0xfffaf2, 0.9);
            scene.add(ambientLight);
            light = new THREE.PointLight(0xffffff, 0.8, 18);
            light.position.set(0,2,4);
            light.shadow.camera.near = 0.1;
            light.shadow.camera.far = 25;
            scene.add(light);
            
//            //CONTROLS
//            var controls = new THREE.OrbitControls( camera );
//            controls.update(); 

            //PARTICLES
            particleSystem = createParticleSystem();
            scene.add(particleSystem);

           
            function createParticleSystem() {
                var particleCount = 1000;

                var particles = new THREE.Geometry();

                for (var p = 0; p < particleCount; p++) {

                // This will create the range of the points
                var x = Math.random() * 20 - 10;
                var y = Math.random() * 40 - 20;
                var z = Math.random() * 0.4 - 0;

                var particle = new THREE.Vector3(x, y, z);

                particles.vertices.push(particle);
                }

                // Create the material that will be used to render each vertex of the geometry
                var particleMaterial = new THREE.PointsMaterial(
                    {color: 0xffffff, 
                     size: 0.05,
                    });

                // Create the particle system
                particleSystem = new THREE.Points(particles, particleMaterial);
                return particleSystem;	
            }

            
            function animateParticles() {
                var verts = particleSystem.geometry.vertices;
                for(var i = 0; i < verts.length; i++) {
                    var vert = verts[i];
                    if (vert.y < -20) {
                        vert.y = Math.random() * 40 - 20;
                        }
                    vert.y = vert.y - (0.7 * deltaTime);
                    }
                particleSystem.geometry.verticesNeedUpdate = true;
            }
            
          //GTLF FILE
            var loader = new THREE.GLTFLoader();
                loader.load( 'https://raw.githubusercontent.com/VascaM/THREE.js-Snowman-Project/main/Assets/3D_Snowman/snowmen.gltf', function ( gltf ) {
                      scene.add(gltf.scene);
                      mixer = new THREE.AnimationMixer(gltf.scene);
                      action = mixer.clipAction(gltf.animations[0]);
                      action.setLoop( THREE.LoopOnce );
                      gltf.scene.position.set(6.4, -1.85, 0.5);
                      gltf.scene.scale.set(0.3, 0.3, 0.22);
                      gltf.scene.rotation.y = -1;
                      model = gltf.scene;
                }
            );
            
            //MOUSE CLICK
            var raycaster = new THREE.Raycaster();
            var mouse = new THREE.Vector2();
            var intersects = [];
            renderer.domElement.addEventListener("click", onClick, false);

            function onClick(event) {
              mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
              mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
              raycaster.setFromCamera(mouse, camera);
              intersects = raycaster.intersectObject(cube);
              if (intersects.length > 0){
                 if ( action !== null ) {
                action.stop();
                action.play();
                 }
              }
            }
            
            //ONZICHTBARE CUBE
            var geometrycube = new THREE.BoxGeometry( 1, 1.5, 0.1 );
			var materialcube = new THREE.MeshBasicMaterial( { color: 0x00ff00, transparent:true, opacity:0.0,  } );
			var cube = new THREE.Mesh( geometrycube, materialcube );
            cube.position.set(6.4, -1.5, 0.9);
			scene.add( cube );
            
            //ANIMATE LOOP
            var animate = function () {
              requestAnimationFrame( animate );
              renderer.render( scene, camera );

              var delta = clock2.getDelta();

              if (mixer !== null) {
                mixer.update(delta);
              };
                
                deltaTime = clock1.getDelta();
                animateParticles();

                if (plane1.position.x >= targetPositionX1) {
                plane1.position.x -= 0.00094;}
                if (plane2.position.x >= targetPositionX2) {
                plane2.position.x -= 0.00203;}
                if (plane3.position.x >= targetPositionX3) {
                plane3.position.x -= 0.0032;}
                if (plane4.position.x >= targetPositionX4) {
                plane4.position.x -= 0.005;}
                if ( model && ( model.position.x >= targetPositionX5 ) ) {
                  model.position.x -= 0.0032;
                }
                if (cube.position.x >= targetPositionX5) {
                cube.position.x -= 0.0032;}
                
            };

            animate();
		
		</script>
	</body> 
</html>
