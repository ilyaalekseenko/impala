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
            mouseMoveInterval: 14, // интервал в миллисекундах (60 FPS)
            tempCurrentZ:0,
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
            this.draggedObject = this.createBlueCube(0,0,"#00FFFF");
            this.draggedObject1 = this.createBlueCube(20,1,"#90EE90");

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




        createBlueCube(zCoord,yourCustomID,color) {
            const cubeGroup = new THREE.Group();
            const boxGeometry1 = new THREE.BoxGeometry(10, 10, 10);
            const material1 = new THREE.MeshStandardMaterial({ color: color });
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
                    this.updateDraggedObjectPosition(event);
                });
            }
        },

        updateDraggedObjectPosition(event) {
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

            const isIntersectingZ = currentCubeBoundingBox.intersectsBox(otherCubeBoundingBox);
            const smoothingFactor = 0.2;
            let side=this.whichSide(this.draggedObject.position.z,this.draggedObject1.position.z,this.draggedObject.position.x,this.draggedObject1.position.x)

            //(this.draggedObject.position.z - deltaZ < 0) отвечает за оттаскивание куба когда он уже присоединён

            if (isIntersectingZ) {
              //   console.log('пересечение с дальней гранью +5')
                // console.log(side)
                //если сторона правая
                     //здесь добавить точку пересечения с ближайшей координатой
                if(side.right)
                {
                    // console.log(this.draggedObject.position.z)
                    // console.log(deltaZ)
                    // console.log(this.draggedObject.position.x)
                    // console.log(deltaX)



                    //тянем налево
                    if(this.draggedObject.position.z - deltaZ < 0)
                    {
                      let coordD=this.getCoordGranCube(this.draggedObject)
                      let coordD1= this.getCoordGranCube(this.draggedObject1)
                        if((Math.abs(this.draggedObject.position.x - deltaX) > 1))
                        {
                         //   console.log('двигаемся в другую сторону')

                                // Используйте lerp для более плавного движения
                                this.draggedObject.position.x += (this.startPosition.x + deltaX - this.draggedObject.position.x) * smoothingFactor;
                            //чтобы не залазить на куб
                            if (this.draggedObject.position.z<30)
                            {
                                this.draggedObject.position.z += (this.startPosition.z + deltaZ - this.draggedObject.position.z) * smoothingFactor;
                                //магнит к осям граней
                            }
                          //  console.log(coordD[19].x)
                         //   console.log(coordD1[15].x)
                            if((Math.abs(coordD[19].x-coordD1[15].x)) < 2)
                            {

// определяем Положение задних граней и пытаемся сопоставить их на одну линию короч
                  //              const zPositionCube1 = this.draggedObject.depth / 2;
                    //            const zPositionCube2 = -this.draggedObject1.depth / 2;

// Задний куб (draggedObject)
                    //            this.draggedObject.position.z = zPositionCube1;

// Второй куб (draggedObject1)
                    //            this.draggedObject1.position.z = zPositionCube2;
                                    //this.draggedObject.position.x= this.draggedObject.position.x-(Math.abs(coordD[19].x-coordD1[15].x))
                            }

                        }
                        else
                        {
                           // console.log('ПРИМАГНИТИЛСЯ')
                            // this.draggedObject.position.x = 20;
                            this.draggedObject.position.z = 30;
                            if(this.draggedObject.position.z - deltaZ< (-10))
                            {
                                this.draggedObject.position.z = this.draggedObject.position.z + 20;
                            }
                        }

                    }
                    //тянем в любую другую сторону
                    else
                    {

                       // console.log('другая сторона')
                        if (customID === 0) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject.position.x += (this.startPosition.x + deltaX - this.draggedObject.position.x) * smoothingFactor;
                            this.draggedObject.position.y += (this.startPosition.y + deltaY - this.draggedObject.position.y) * smoothingFactor;
                            this.draggedObject.position.z += (this.startPosition.z + deltaZ - this.draggedObject.position.z) * smoothingFactor;
                        } else if (customID === 1) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject1.position.x += (this.startPosition.x + deltaX - this.draggedObject1.position.x) * smoothingFactor;
                            this.draggedObject1.position.y += (this.startPosition.y + deltaY - this.draggedObject1.position.y) * smoothingFactor;
                            this.draggedObject1.position.z += (this.startPosition.z + deltaZ - this.draggedObject1.position.z) * smoothingFactor;
                        }
                    }

                }
                //если сторона левая
                if((side.left))
                {
                    //тянем направо
                    if(this.draggedObject.position.z - deltaZ > 0)
                    {
                        this.draggedObject.position.x = 20;
                        this.draggedObject.position.z = 50;
                        if(this.draggedObject.position.z - deltaZ> (10))
                        {
                            this.draggedObject.position.z = this.draggedObject.position.z - 20;
                        }
                    }
                    //тянем в любую другую сторону
                    else
                    {
                        if (customID === 0) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject.position.x += (this.startPosition.x + deltaX - this.draggedObject.position.x) * smoothingFactor;
                            this.draggedObject.position.y += (this.startPosition.y + deltaY - this.draggedObject.position.y) * smoothingFactor;
                            this.draggedObject.position.z += (this.startPosition.z + deltaZ - this.draggedObject.position.z) * smoothingFactor;
                        } else if (customID === 1) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject1.position.x += (this.startPosition.x + deltaX - this.draggedObject1.position.x) * smoothingFactor;
                            this.draggedObject1.position.y += (this.startPosition.y + deltaY - this.draggedObject1.position.y) * smoothingFactor;
                            this.draggedObject1.position.z += (this.startPosition.z + deltaZ - this.draggedObject1.position.z) * smoothingFactor;
                        }
                    }

                }
                //если сторона низ
                if(side.down)
                {
                    //тянем вверх
                    if(this.draggedObject.position.x - deltaX > 0)
                    {
                        this.draggedObject.position.x = 30;
                        this.draggedObject.position.z = 40;
                        if(this.draggedObject.position.x - deltaX> (10))
                        {
                            this.draggedObject.position.x = this.draggedObject.position.x - 20;
                        }
                    }
                    //тянем в любую другую сторону
                    else
                    {
                        if (customID === 0) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject.position.x += (this.startPosition.x + deltaX - this.draggedObject.position.x) * smoothingFactor;
                            this.draggedObject.position.y += (this.startPosition.y + deltaY - this.draggedObject.position.y) * smoothingFactor;
                            this.draggedObject.position.z += (this.startPosition.z + deltaZ - this.draggedObject.position.z) * smoothingFactor;
                        } else if (customID === 1) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject1.position.x += (this.startPosition.x + deltaX - this.draggedObject1.position.x) * smoothingFactor;
                            this.draggedObject1.position.y += (this.startPosition.y + deltaY - this.draggedObject1.position.y) * smoothingFactor;
                            this.draggedObject1.position.z += (this.startPosition.z + deltaZ - this.draggedObject1.position.z) * smoothingFactor;
                        }
                    }
                }
                //если сторона верх
                if(side.up)
                {
                    //тянем вниз
                    if(this.draggedObject.position.x - deltaX < 0)
                    {
                        this.draggedObject.position.x = 10;
                        this.draggedObject.position.z = 40;
                        if(deltaX-this.draggedObject.position.x > (10))
                        {
                             this.draggedObject.position.x = this.draggedObject.position.x + 20;
                        }
                    }
                    //тянем в любую другую сторону
                    else
                    {
                        if (customID === 0) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject.position.x += (this.startPosition.x + deltaX - this.draggedObject.position.x) * smoothingFactor;
                            this.draggedObject.position.y += (this.startPosition.y + deltaY - this.draggedObject.position.y) * smoothingFactor;
                            this.draggedObject.position.z += (this.startPosition.z + deltaZ - this.draggedObject.position.z) * smoothingFactor;
                        } else if (customID === 1) {
                            // Используйте lerp для более плавного движения
                            this.draggedObject1.position.x += (this.startPosition.x + deltaX - this.draggedObject1.position.x) * smoothingFactor;
                            this.draggedObject1.position.y += (this.startPosition.y + deltaY - this.draggedObject1.position.y) * smoothingFactor;
                            this.draggedObject1.position.z += (this.startPosition.z + deltaZ - this.draggedObject1.position.z) * smoothingFactor;
                        }
                    }
                }


            } else {
                if (customID === 0) {
                    // Используйте lerp для более плавного движения
                    this.draggedObject.position.x += (this.startPosition.x + deltaX - this.draggedObject.position.x) * smoothingFactor;
                    this.draggedObject.position.y += (this.startPosition.y + deltaY - this.draggedObject.position.y) * smoothingFactor;
                    this.draggedObject.position.z += (this.startPosition.z + deltaZ - this.draggedObject.position.z) * smoothingFactor;
                } else if (customID === 1) {
                    // Используйте lerp для более плавного движения
                    this.draggedObject1.position.x += (this.startPosition.x + deltaX - this.draggedObject1.position.x) * smoothingFactor;
                    this.draggedObject1.position.y += (this.startPosition.y + deltaY - this.draggedObject1.position.y) * smoothingFactor;
                    this.draggedObject1.position.z += (this.startPosition.z + deltaZ - this.draggedObject1.position.z) * smoothingFactor;
                }
            }
        }


        ,


        getCoordGranCube(draggedObject)
        {
            const draggedObjectMesh = draggedObject.children[0]; // Меш куба
            const draggedObjectGeometry = draggedObjectMesh.geometry; // Буферная геометрия
            draggedObjectGeometry.computeBoundingBox(); // Обновляем ограничивающий объем для получения корректных вершин
            const draggedObjectVertices = [];

            for (let i = 0; i < draggedObjectGeometry.attributes.position.count; i++) {
                const vertex = new THREE.Vector3();
                vertex.fromBufferAttribute(draggedObjectGeometry.attributes.position, i);
                vertex.applyMatrix4(draggedObjectMesh.matrixWorld);
                draggedObjectVertices.push(vertex);
            }
            //console.log('Координаты вершин draggedObject:', draggedObjectVertices);
            return draggedObjectVertices;
        },

        //метод определяющий сторону с которой идёт куб
        whichSide(currentCubeZ, otherCubeZ,currentCubeX,otherCubeX) {
            // console.log('currentCubeZ')
            // console.log(currentCubeZ)
            // console.log('otherCubeZ')
            // console.log(otherCubeZ)
            // console.log('currentCubeX')
            // console.log(currentCubeX)
            // console.log('otherCubeX')
            // console.log(otherCubeX)
            const result = {};
            //определим право или лево
            if ((currentCubeZ - (otherCubeZ + 20)) > 0) {
                result.left = true;
                result.right = false;
            } else {
                result.left = false;
                result.right = true;
            }
            //определим низ или верх
            if ((currentCubeX - otherCubeX ) < 0) {
                result.down = false;
                result.up = true;
            } else {
                result.down = true;
                result.up = false;
            }

            //окончательно определим к какой стороне приближается куб
            if((result.down === true)&&(result.right === true))
            {
               if(Math.abs(currentCubeZ-(otherCubeZ+20))<Math.abs(currentCubeX-otherCubeX))
               {
                   // console.log('X > Z')
                   // console.log (Math.abs(currentCubeZ-(otherCubeZ+20)))
                   // console.log (Math.abs(currentCubeX-otherCubeX))
                   result.down=true
                   result.right=false
               }
               else
               {
                   // console.log('X < Z')
                   // console.log(currentCubeZ-otherCubeZ)
                   // console.log(currentCubeX-otherCubeX)
                   result.down=false
                   result.right=true
               }
            }
            if((result.down === true)&&(result.left === true))
            {
                if(Math.abs(currentCubeZ-(otherCubeZ+20))<Math.abs(currentCubeX-otherCubeX))
                {
                    result.down=true
                    result.left=false
                }
                else
                {
                    result.down=false
                    result.left=true
                }
            }
            if((result.up === true)&&(result.left === true))
            {
                if(Math.abs(currentCubeZ-(otherCubeZ+20))<Math.abs(currentCubeX-otherCubeX))
                {
                    result.up=true
                    result.left=false
                }
                else
                {
                    result.up=false
                    result.left=true
                }
            }
            if((result.up === true)&&(result.right === true))
            {
                if(Math.abs(currentCubeZ-(otherCubeZ+20))<Math.abs(currentCubeX-otherCubeX))
                {
                    result.up=true
                    result.right=false
                }
                else
                {
                    result.up=false
                    result.right=true
                }
            }
            return result;
        },

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
