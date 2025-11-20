<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Model View - {{ $color }}</title>
    <style>
        body { margin: 0; overflow: hidden; }
        canvas { display: block; width: 100%; height: 1000px; }

        .choise {
            height: 150px;
            cursor: pointer;
            z-index: 999;
            border-radius: 15px; 
            margin: 0 10px;
        }
        .choise:hover {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        }

        .choise-container {
            position: absolute;
            bottom: 5px;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
    </style>
</head>
<body>
    <canvas id="modelCanvas"></canvas>

    <div class="choise-container">
        <img src="{{ asset('images/red.png') }}" alt="red" class="choise" onclick="changeColor('red')">
        <img src="{{ asset('images/yellow.png') }}" alt="yellow" class="choise" onclick="changeColor('yellow')">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

    <script>
        let scene, camera, renderer, controls;
        let objects = {}; // เก็บโมเดลที่โหลดแล้ว
        let currentObject = null;

        function init() {
            const canvas = document.getElementById('modelCanvas');

            scene = new THREE.Scene();
            scene.background = new THREE.Color(0xf0f0f0);

            camera = new THREE.PerspectiveCamera(75, window.innerWidth / 1000, 0.1, 1000);
            camera.position.set(0, 2, 80);

            const dirLight = new THREE.DirectionalLight(0xffffff, 1);
            dirLight.position.set(5,5,5);
            scene.add(dirLight);

            const ambient = new THREE.AmbientLight(0x404040, 1.5);
            scene.add(ambient);

            renderer = new THREE.WebGLRenderer({ antialias: true, canvas: canvas });
            renderer.setSize(window.innerWidth, 1000);

            controls = new THREE.OrbitControls(camera, renderer.domElement);

            // โหลดสีเริ่มต้นจาก Blade
            changeColor("{{ $color }}");

            window.addEventListener('resize', onResize);
            animate();
        }

        function changeColor(color) {
            if (!scene) return;

            // ลบ object เก่า
            if (currentObject) {
                scene.remove(currentObject);
            }

            // ถ้าโหลดแล้วใช้ clone
            if (objects[color]) {
                currentObject = objects[color].clone();
                scene.add(currentObject);
                return;
            }

            // โหลด MTL+OBJ
            const mtlLoader = new THREE.MTLLoader();
            mtlLoader.setPath(`/models/${color}/`);
            mtlLoader.load(`${color}.mtl`, function(materials) {
                materials.preload();
                const objLoader = new THREE.OBJLoader();
                objLoader.setMaterials(materials);
                objLoader.setPath(`/models/${color}/`);
                objLoader.load(`${color}.obj`, function(object) {
                    object.scale.set(1,1,1);
                    object.position.y = -1;
                    objects[color] = object; // เก็บโมเดล
                    currentObject = object.clone();
                    scene.add(currentObject);
                    console.log("Model loaded:", color);
                });
            });
        }

        function onResize() {
            camera.aspect = window.innerWidth / 1000;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, 1000);
        }

        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }

        init();
    </script>
</body>
</html>
