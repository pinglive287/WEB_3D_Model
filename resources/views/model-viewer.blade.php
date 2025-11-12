<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Model Viewer - Laravel + Three.js</title>
    <style>
    body {
        margin: 0;
        overflow: hidden;
        background-color: #f4f4f4;
    }

    canvas {
        display: block;
        width: 100%;
        height: 100vh;
    }
    </style>
</head>

<body>

    <!-- โหลด Three.js และ loader ต่าง ๆ -->
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>


    <script>
    let scene, camera, renderer, controls;

    function init() {
        // สร้าง scene
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf0f0f0);

        // กล้อง
        camera = new THREE.PerspectiveCamera(
            75,
            window.innerWidth / window.innerHeight,
            0.1,
            1000
        );
        camera.position.set(0, 2, 50);

        // แสง
        const light = new THREE.DirectionalLight(0xffffff, 1);
        light.position.set(5, 5, 5);
        scene.add(light);

        const ambient = new THREE.AmbientLight(0x404040, 1.5);
        scene.add(ambient);

        // Renderer
        renderer = new THREE.WebGLRenderer({
            antialias: true
        });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // Controls (หมุนดูโมเดลได้)
        controls = new THREE.OrbitControls(camera, renderer.domElement);

        // โหลดไฟล์ MTL + OBJ
        const mtlLoader = new THREE.MTLLoader();
        mtlLoader.setPath('/models/');
        mtlLoader.load('chair.mtl', (materials) => {
            materials.preload();

            const objLoader = new THREE.OBJLoader();
            objLoader.setMaterials(materials);
            objLoader.setPath('/models/');
            objLoader.load('chair.obj', (object) => {
                object.scale.set(1, 1, 1);
                object.position.y = -1; // ยกขึ้นจากพื้นเล็กน้อย
                scene.add(object);
            });
        });

        // ปรับขนาดเมื่อเปลี่ยนขนาดหน้าจอ
        window.addEventListener('resize', onWindowResize);
    }

    function onWindowResize() {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    }

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }

    init();
    animate();
    </script>

</body>

</html>