<template>
    <div
        id="scene"
        ref="scene"
        @mousedown="onMouseDown"
        @mouseup="onMouseUp"
        @mousemove="onMouseMove"
    ></div>
</template>

<script>
import * as THREE from "three";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";

export default {
    data() {
        return {
            isDragging: false,
            startPosition: new THREE.Vector3(),
            draggedObject: null,
            draggedObject1: null,
            scene: null,
            camera: null,
            controls: null,
            renderer: null,
            objectCustomIDMap: new Map(),
            lastMouseMoveTime: 0,
            mouseMoveInterval: 16, // интервал в миллисекундах (60 FPS)
        };
    },
    mounted() {
        this.initThreeScene();
    },
    methods: {
        initThreeScene() {
            this.scene = new THREE.Scene();
            this.scene.background = new THREE.Color(0xffffff);

            this.camera = new THREE.PerspectiveCamera(
                45,
                window.innerWidth / window.innerHeight,
                0.1,
                1000
            );
            this.renderer = new THREE.WebGLRenderer();
            this.renderer.setSize(window.innerWidth, window.innerHeight);
            this.$refs.scene.appendChild(this.renderer.domElement);

            const gridHelper = new THREE.GridHelper(300, 50);
            gridHelper.rotation.x = Math.PI;
            gridHelper.rotation.y = Math.PI;
            this.scene.add(gridHelper);

            this.createRedCube();
            this.createCabin();
            this.draggedObject = this.createBlueCube(0,0);
            this.draggedObject1 = this.createBlueCube(20,1);

            this.scene.add(this.draggedObject);
            this.scene.add(this.draggedObject1);

            const ambientLight = new THREE.AmbientLight(0x404040);
            ambientLight.intensity = 20;
            this.scene.add(ambientLight);

            const spotLight = new THREE.SpotLight(0xffffff);
            spotLight.position.set(-40, 60, -10);
            this.scene.add(spotLight);

            this.camera.position.set(40, 40, 100);
            this.camera.lookAt(0, 0, 0);

            this.controls = new OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enableDamping = true;
            this.controls.dampingFactor = 0.1;
            this.controls.enableZoom = true;

            this.renderer.domElement.style.cursor = "grab";

            this.renderer.domElement.addEventListener(
                "mousemove",
                (event) => this.onCanvasMouseMove(event, this.draggedObject)
            );
            this.renderer.domElement.addEventListener(
                "mouseout",
                this.onCanvasMouseOut
            );

            const animate = () => {
                requestAnimationFrame(animate);

                if (!this.isDragging) {
                    this.controls.update();
                }

                this.renderer.render(this.scene, this.camera);
            };

            // const axesHelper = new THREE.AxesHelper(150);
            // this.scene.add(axesHelper);





            animate();
        },
        createRedCube() {
            const boxGeometry = new THREE.BoxGeometry(20, 20, 80);
            const edgesMaterial = new THREE.LineBasicMaterial({ color: 0xff0000 });
            const edges = new THREE.LineSegments(
                new THREE.EdgesGeometry(boxGeometry),
                edgesMaterial
            );
            edges.position.set(0, 10, 20);
            edges.rotation.x = Math.PI;
            edges.rotation.y = Math.PI;
            this.scene.add(edges);

            const redMaterial = new THREE.MeshStandardMaterial({ color: 0xff0000 });
            const bottomPlane = new THREE.Mesh(
                new THREE.PlaneGeometry(20, 80),
                redMaterial
            );
            bottomPlane.position.set(0, 0, 20);
            bottomPlane.rotation.x = -Math.PI / 2;
            bottomPlane.rotation.y = 0;


            this.scene.add(bottomPlane);
        },

        createCabin() {
            const pointsArray = [
                //нижний квадрат
                new THREE.Vector3(10, 0, 65),
                new THREE.Vector3(10, 0, 80),
                new THREE.Vector3(-10, 0, 80),
                new THREE.Vector3(-10, 0, 65),
                new THREE.Vector3(10, 0, 65),
                //задняя стойка
                new THREE.Vector3(10, 20, 65),
                //верхний квадрат
                new THREE.Vector3(-10, 20, 65),
                new THREE.Vector3(-10, 20, 75),
                new THREE.Vector3(10, 20, 75),
                new THREE.Vector3(10, 20, 65),

                new THREE.Vector3(10, 20, 75),
                new THREE.Vector3(10, 10, 80),
                new THREE.Vector3(10, 0, 80),
                new THREE.Vector3(10, 10, 80),
                new THREE.Vector3(-10, 10, 80),
                new THREE.Vector3(-10, 0, 80),
                new THREE.Vector3(-10, 10, 80),
                new THREE.Vector3(-10, 20, 75),
                new THREE.Vector3(-10, 20, 65),
                new THREE.Vector3(-10, 0, 65),

            ];
// Создаем геометрию для линии (из массива точек)
            const lineGeometry = new THREE.BufferGeometry().setFromPoints(pointsArray);
// Создаем материал для линии
            const lineMaterial = new THREE.LineBasicMaterial({ color: 0x0000ff });
// Создаем объект Line
            const line = new THREE.Line(lineGeometry, lineMaterial);
// Добавляем линию в сцену
            this.scene.add(line);

        }

        ,




        createBlueCube(zCoord,yourCustomID) {
            const cubeGroup = new THREE.Group();
            const boxGeometry1 = new THREE.BoxGeometry(10, 10, 10);
            const material1 = new THREE.MeshStandardMaterial({ color: "#90EE90" });
            const cube = new THREE.Mesh(boxGeometry1, material1);
            cube.position.set(0, 0, zCoord);
            const edgesGeometry = new THREE.EdgesGeometry(boxGeometry1);
            const edgesMaterial = new THREE.LineBasicMaterial({
                color: 0x000000,
                linewidth: 2,
            });
            const edges = new THREE.LineSegments(edgesGeometry, edgesMaterial);
            edges.position.set(0, 0, zCoord);
            // cube.customID = yourCustomID; // Задайте ваш уникальный customID
            // edges.customID = yourCustomID; // Задайте ваш уникальный customID
            this.objectCustomIDMap.set(cube, yourCustomID);
            this.objectCustomIDMap.set(edges, yourCustomID);
            cubeGroup.add(cube);
            cubeGroup.add(edges);
            cubeGroup.position.set(40-zCoord, 5, zCoord);
            cubeGroup.rotation.x = Math.PI;
            cubeGroup.rotation.y = Math.PI;

            this.scene.add(cubeGroup);
            return cubeGroup;
        },

        onMouseDown(event) {
            const raycaster = new THREE.Raycaster();
            const mouse = new THREE.Vector2();

            mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
            mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

            raycaster.setFromCamera(mouse, this.camera);
            const intersects = raycaster.intersectObjects([
                this.draggedObject,
                this.draggedObject1,
            ]);

            if (intersects.length > 0) {
                this.isDragging = true;
                this.startPosition.copy(intersects[0].object.position);
                this.draggedObjectNew = intersects[0].object;  // Устанавливаем объект, который будет перемещаться
                //  console.log('this.draggedObjectNew.customID')
                //  console.log(this.draggedObjectNew.customID)
                // console.log('this.draggedObjectNew1.customID')
                this.controls.enabled = false;
                this.renderer.domElement.style.cursor = "grabbing";
            }
        },
        onMouseUp() {
            this.isDragging = false;
            this.controls.enabled = true;
            this.renderer.domElement.style.cursor = "grab";
        },

        onMouseMove(event) {
            const now = Date.now();
            if (now - this.lastMouseMoveTime < this.mouseMoveInterval) {
                return;
            }
            this.lastMouseMoveTime = now;

            if (this.isDragging && this.draggedObjectNew) {
                requestAnimationFrame(() => {
                    const raycaster = new THREE.Raycaster();
                    const mouse = new THREE.Vector2();

                    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
                    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

                    raycaster.setFromCamera(mouse, this.camera);
                    const plane = new THREE.Plane(new THREE.Vector3(0, 1, 0), 0);
                    const intersectPoint = new THREE.Vector3();
                    raycaster.ray.intersectPlane(plane, intersectPoint);

                    const deltaX = intersectPoint.x - this.startPosition.x;
                    const deltaY = intersectPoint.y - this.startPosition.y + 5;
                    const deltaZ = intersectPoint.z - this.startPosition.z;

                    const customID = this.objectCustomIDMap.get(this.draggedObjectNew);

                    const otherCube = customID === 0 ? this.draggedObject1 : this.draggedObject;

                    const otherCubeBoundingBox = new THREE.Box3().setFromObject(otherCube);
                    const currentCubeBoundingBox = new THREE.Box3().setFromObject(this.draggedObjectNew);
                    otherCubeBoundingBox.expandByScalar(5);

                  //  const isIntersectingX = otherCubeBoundingBox.intersectsBox(currentCubeBoundingBox);
                 //   const isIntersectingY = otherCubeBoundingBox.intersectsBox(currentCubeBoundingBox);
                    const isIntersectingZ = currentCubeBoundingBox.intersectsBox(otherCubeBoundingBox);

                    //определить грань пересечения
                    //определить размеры куба и переместить куб на правильное расстояние
                    if ( isIntersectingZ) {
                        if (customID === 0) {
                             console.log('ЕСТЬ ПЕРЕСЕЧЕНИЕ')
                                 //нужно определить координаты точек нижней плоскости куба
                           // this.draggedObject.position.y = this.draggedObject.position.y;
                                //  this.draggedObject.position.z = this.draggedObject.position.z-1;
                         //            this.draggedObject.position.x = this.draggedObject.position.x+1;

                        }

                    }
                 //   else {
                        if (customID === 0) {
                            this.draggedObject.position.x = this.startPosition.x + deltaX;
                            this.draggedObject.position.y = this.startPosition.y + deltaY;
                            this.draggedObject.position.z = this.startPosition.z + deltaZ;
                        } else if (customID === 1) {
                            this.draggedObject1.position.x = this.startPosition.x + deltaX;
                            this.draggedObject1.position.y = this.startPosition.y + deltaY;
                            this.draggedObject1.position.z = this.startPosition.z + deltaZ;
                        }
                  //  }

                    // if ( isIntersectingX) {
                    //     if (customID === 0) {
                    //         // console.log('ЕСТЬ ПЕРЕСЕЧЕНИЕ X')
                    //         // this.draggedObject.position.y = this.draggedObject.position.y;
                    //         //  this.draggedObject.position.z = this.draggedObject.position.z-1;
                    //         const intersectsFaces = raycaster.intersectObject(otherCube, true);
                    //         if (intersectsFaces.length > 0) {
                    //             const intersect = intersectsFaces[0];
                    //             const face = intersect.face; // Грань, с которой произошло пересечение
                    //
                    //             // Далее вы можете использовать информацию о грани для определения, с какой гранью произошло пересечение
                    //             console.log('Номер грани: ', face);
                    //         }
                    //         this.draggedObject.position.z = this.draggedObject.position.x+deltaZ;
                    //     }
                    //
                    // }
                    // else {
                    //     if (customID === 0) {
                    //         this.draggedObject.position.x = this.startPosition.x + deltaX;
                    //         this.draggedObject.position.y = this.startPosition.y + deltaY;
                    //         this.draggedObject.position.z = this.startPosition.z + deltaZ;
                    //     } else if (customID === 1) {
                    //         this.draggedObject1.position.x = this.startPosition.x + deltaX;
                    //         this.draggedObject1.position.y = this.startPosition.y + deltaY;
                    //         this.draggedObject1.position.z = this.startPosition.z + deltaZ;
                    //     }
                    // }



                });
            }
        }







        ,





        onCanvasMouseMove(event, object) {
            const raycaster = new THREE.Raycaster();
            const mouse = new THREE.Vector2();

            mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
            mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

            raycaster.setFromCamera(mouse, this.camera);

            if (!object || !object.geometry) {
                return;
            }

            const intersectsFaces = raycaster.intersectObject(object, true);
            const plane = new THREE.Plane(new THREE.Vector3(0, 1, 0), 0);
            const intersectsPlane = intersectsFaces
                .filter((intersect) => intersect.object === object)
                .map((intersect) => {
                    const planeIntersect = new THREE.Vector3();
                    raycaster.ray.intersectPlane(plane, planeIntersect);
                    return planeIntersect;
                });

            if (
                intersectsFaces.length > 0 ||
                (intersectsPlane.length > 0 && intersectsPlane[0].distance > 0)
            ) {
                this.renderer.domElement.style.cursor = "pointer";
            } else {
                this.renderer.domElement.style.cursor = "grab";
            }
        },
        onCanvasMouseOut() {
            this.renderer.domElement.style.cursor = "grab";
        },
    },
};
</script>

<style>
#scene {
    position: absolute;
    top: 0;
    left: 0;
}
</style>
