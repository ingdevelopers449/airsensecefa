**PROYECTO FORMATIVO:** IMPLEMENTACIÓN DE UN SISTEMA WEB DE MONITOREO EN TIEMPO REAL DE LA CALIDAD DEL AIRE, MEDIANTE TECNOLOGIAS IoT Y PYTHON, EN EL CENTRO DE FORMACIÓN AGROINDUSTRIAL LA ANGOSTURA.

**PRESENTADO POR:**

* LUIS FELIPE LOZADA BASTIDAS
* MICHAEL GUSTAVO CASTAÑO PAREJA
* LIZBETH DAYANA DAZA ROGELIS
* ISABELLA SIFUENTES PERDOMO

**PRESENTADO A:**

MARTHA RIVERA

**INSTRUCTORA**

**CENTRO DE FORMACIÓN AGROINDUSTRIAL – REGIONAL HUILA**

**TECNÓLOGO EN ANÁLISIS Y DESARROLLÓ DE SOFTWARE**

**FICHA: 3312595**

**JUNIO DE 2026**

**PROBLEMA**

En el Centro de Formación Agroindustrial “La Angostura” (CEFA), la falta de sistemas automatizados para monitorear la calidad del aire ambiental en cualquier punto del centro de formación —incluyendo ambientes académicos, laboratorios, zonas agropecuarias, áreas administrativas y espacios exteriores— impide conocer el estado real de las condiciones atmosféricas en toda la institución.

Debido a la permanencia prolongada de instructores y aprendices, el dióxido de carbono **CO₂** acumulado por la respiración humana supera frecuentemente los 1000 ppm saludables.

Esto genera un ambiente pesado que provoca fatiga, dolores de cabeza, somnolencia y una reducción de hasta el 50% en la retención cognitiva y la concentración académica.

Al no existir mediciones técnicas en tiempo real, las decisiones de ventilación se toman de manera empírica ("al ojo"), abriendo o cerrando ventanas sin un criterio científico real, lo que vulnera los estándares modernos de Seguridad y Salud en el Trabajo (EHS) y afecta el bienestar de la comunidad educativa.

**SOLUCIÓN**

El propósito de este proceso es identificar, recolectar y formalizar las necesidades operativas, de seguridad y de visualización del personal del CEFA "La Angostura" y del área de Seguridad y Salud en el Trabajo (EHS), en cualquier ambiente o dependencia del centro de formación y no únicamente en Tecnoparque.

Mediante las técnicas de elicitación propuestas, se busca determinar el alcance exacto para el diseño de un sistema a medida que permita:

* Conocer cómo el usuario final desea interactuar con los datos recolectados.
* Establecer los perfiles, restricciones de acceso y flujos de alertas requeridos por la institución.
* Definir los parámetros de rendimiento y usabilidad necesarios para que la información ambiental sea clara y oportuna para la comunidad educativa del CEFA.

**ALCANCE Y EXCLUSIONES**

Con el fin de delimitar de forma explícita el proyecto, quedan fuera del alcance de AirSense CEFA los siguientes puntos:

AirSense CEFA sí incluye el monitoreo de nodos IoT en cualquier lugar del CEFA "La Angostura" —espacios cerrados, abiertos, exteriores, zonas agropecuarias, hangares, centros de acopio y áreas administrativas—, sin restringirse a ambientes académicos ni a zonas específicas como Tecnoparque. El sistema mide las variables ambientales (CO₂, temperatura y humedad) en cualquier punto del CEFA donde se instale un nodo IoT.

* El desarrollo de la aplicación móvil nativa se limita a la plataforma Android; no incluye una versión nativa para iOS (RF-42).
* No contempla integración con la plataforma SENA SOFIA Plus ni con el sistema de matrículas o registro académico.
* No mide contaminantes distintos a CO₂, temperatura y humedad (no incluye material particulado, compuestos orgánicos volátiles u otros gases).
* En su versión actual, el sistema está configurado y desplegado para el CEFA "La Angostura", Regional Huila; sin embargo, su arquitectura modular permite que sea adoptado o adaptado por otros Centros de Formación del SENA que lo requieran, siempre que cuenten con la infraestructura IoT necesaria.
* No reemplaza los protocolos oficiales de Seguridad y Salud en el Trabajo (EHS); es el EHS quien define y entrega el manual de contingencia con lo que se debe hacer en cada caso, y la plataforma únicamente lo consulta y despliega, sin sustituir la gestión institucional de EHS.
* El módulo predictivo es una herramienta de apoyo a la toma de decisiones; no constituye un diagnóstico médico ni garantiza una precisión absoluta.

**TECNICAS DE ELICITACIÓN**

**Encuesta Técnica**

**Sección A – Administrador**

1. Cuando usted ingrese a la plataforma web, ¿Cual considera que es la mejor forma de visualizar los ambientes de formación?; con todos los ambientes de formación registrados para saber cuál está bien y cuál no, ¿o solo le interesa ver un ambiente a la vez?

**RTA:** Prefiero una visualización general y ágil mediante un mapa digital interactivo del CEFA "La Angostura" que muestre, sobre el plano del centro, todos los ambientes de formación monitoreados al mismo tiempo. Cada punto del mapa debe representar el estado de la calidad del aire mediante el sistema semáforo (verde, amarillo, rojo), calculado a partir de las variables de CO₂, temperatura y humedad captadas por los sensores, y debe señalar puntualmente el punto donde se encuentra instalado el prototipo dentro de las instalaciones del centro. Es de vital importancia priorizar el monitoreo en puntos críticos y altamente poblados del CEFA bajo criterios específicos de riesgo sanitario; puntualmente, se requiere visibilidad prioritaria en el hangar de ganadería (por emisión de gases animales) y en el centro de acopio (por procesos de descomposición orgánica), garantizando un despliegue inmediato de alarmas si las concentraciones de CO₂ ponen en riesgo a la población académica.

1. ¿Le gustaría que, además del estado general tipo semáforo, el mapa le permita seleccionar y visualizar por separado el valor específico de cada variable ambiental (CO₂, temperatura o humedad) en cada ambiente?

**RTA:** Sí, sería de gran utilidad contar con un selector dentro del mapa que permita alternar entre las variables ambientales y consultar el valor puntual de cada una en el punto correspondiente, para identificar con precisión cuál variable está generando la alerta sin necesidad de ingresar al detalle de cada ambiente.

1. Si en el futuro las normas de salud cambian, ¿le gustaría tener una opción en el sistema para ajustar usted mismo el nivel en que se dispara la alerta de aire pesado, o prefiere que venga con un valor fijo que no se pueda modificar?

**RTA:** Sí, es un requisito indispensable contar con la autonomía para modificar y parametrizar los límites numéricos de las alertas directamente desde una pantalla de configuración global. El software no debe ser rígido ni venir con valores fijos de fábrica, garantizando así la flexibilidad normativa de la plataforma ante cualquier cambio futuro en las leyes de seguridad y salud en el trabajo (EHS).

1. ¿Desea contar con una pantalla exclusiva donde pueda crear, editar o desactivar de manera directa las cuentas de los instructores y funcionarios del centro, sin depender de soporte técnico externo?

**RTA:** Sí, considero prioritario que el rol de Administrador disponga de un módulo independiente de gestión de usuarios (CRUD). Esto garantizará una administración autónoma del personal del CEFA, permitiendo dar de alta, modificar datos o desactivar cuentas de instructores y funcionarios en tiempo real sin generar dependencias de soporte técnico o proveedores externos.

1. Si usted llega a olvidar su contraseña para entrar a la plataforma, ¿prefiere que el sistema le envíe un enlace seguro a su correo institucional para restablecerla de forma autónoma?

**RTA:** Sí, el sistema debe integrar un flujo de recuperación de credenciales automatizado que envíe un enlace seguro y encriptado directamente al correo electrónico institucional del usuario. Esto optimiza los tiempos de acceso y reduce la carga administrativa de soporte para el restablecimiento de contraseñas de forma autónoma.

1. ¿Qué tan importante es identificar rápidamente qué sensor generó una alerta?

**RTA:** Es de carácter crítico y de máxima prioridad para el éxito del sistema. La plataforma debe indexar e identificar de manera inmediata e inequívoca el nodo de hardware y el ambiente de formación específico que originó la alerta, permitiendo que el personal administrativo o de salud ocupacional actúe de inmediato en el espacio físico sin perder tiempo adivinando la ubicación de la contingencia.

1. ¿Le gustaría acceder al sistema desde dispositivos móviles además de computadores?

**RTA:**  Sí, es un requisito indispensable que la plataforma sea multiplataforma. La interfaz web debe desarrollarse bajo la metodología de diseño adaptativo (Responsive Design), garantizando una visualización correcta, fluida y optimizada tanto en computadores de escritorio como en dispositivos móviles (smartphones y tablets) para facilitar el monitoreo en campo dentro del centro de formación.

1. ¿Le gustaría visualizar únicamente la información de los ambientes de formación o también consultar otros puntos de CEFA?

**RTA:** El sistema no debe limitarse exclusivamente a las aulas de clase tradicionales. La plataforma debe ser modular y escalable, permitiendo mapear, indexar y consultar las métricas de calidad del aire en cualquier punto geográfico o infraestructura del Centro de Formación Agroindustrial “La Angostura” (CEFA) donde se encuentre instalado y transmitiendo un nodo de hardware IoT.

1. ¿Desea que el sistema cierre automáticamente la sesión después de un período de inactividad?

**RTA:** Sí, por políticas estrictas de seguridad informática y protección de datos, el sistema debe incorporar un temporizador de sesión automatizado. Si la plataforma detecta una inactividad total del usuario durante un periodo continuo de 10 minutos, deberá destruir el token de autenticación y redirigir inmediatamente la interfaz hacia la pantalla de Login, evitando accesos no autorizados en equipos compartidos.

1. ¿Considera importante registrar quién realizó cada acción dentro de la plataforma?

**RTA:** Sí, se requiere de forma obligatoria la implementación de un sistema de trazabilidad y auditoría interna (Log de acciones). El backend en Python debe registrar de manera persistente en la base de datos cada acción crítica realizada (creación de usuarios, modificación de umbrales, desactivación de alertas), asociándola de forma unívoca con el nombre del usuario, la fecha, la hora exacta y la dirección IP desde donde se ejecutó.

1. ¿Qué información debería aparecer inmediatamente después de iniciar sesión?

**RTA:** Inmediatamente después de realizar una autenticación exitosa, el sistema debe redirigir al usuario al Dashboard principal interactivo. Esta pantalla inicial de carga debe presentar la vista consolidada de los ambientes de formación y el estado actual del semáforo ambiental en tiempo real, priorizando la visualización de contingencias críticas de forma inmediata y sin pasos intermedios.

1. Para controlar el correcto funcionamiento de las alertas, ¿le gustaría poder registrar en cada ambiente de formación el número máximo de personas permitido en ese salón, de modo que el sistema sepa si un espacio se está sobrepoblando?

**RTA:** De acuerdo con el análisis del entorno, no se establecerá una regla de negocio restrictiva basada en un número fijo de aforo máximo permitido en el software. Dado que la saturación del aire por CO₂ depende de múltiples variables físicas y climáticas dinámicas (tales como la ventilación cruzada natural, el uso de ventiladores o la apertura intermitente de puertas), el sistema evaluará y disparará las alertas basándose exclusivamente en las mediciones químicas directas reportadas por el sensor MH-Z19B en partes por millón (ppm), y no por el conteo estático de personas en el espacio.

1. ¿Le gustaría tener una pantalla simple dentro de la plataforma donde pueda revisar rápidamente si todos los aparatos del centro están encendidos y enviando datos bien, o si alguno se apagó o se desconectó de internet, para saber exactamente a qué ambiente de formación ir a revisar?

**RTA:** Sí. Esa información de conectividad (dispositivo encendido, apagado o desconectado) debe integrarse directamente sobre el mismo mapa digital del CEFA, mediante un indicador visual en el punto correspondiente al ambiente afectado, en lugar de presentarse en una pantalla separada. De esta manera el Administrador identifica de un solo vistazo tanto el estado ambiental (semáforo) como el estado técnico del dispositivo, y sabe exactamente a qué ambiente de formación acudir.

1. Para que el sistema no solo muestre gráficos en pantalla, sino que sea capaz de anticiparse a los problemas, ¿le gustaría que la plataforma cuente con un asistente inteligente (Inteligencia Artificial) que aprenda automáticamente del comportamiento del salón y prediga con anticipación en qué momento exacto el aire se volverá pesado, sugiriendo la acción exacta que debe tomar el instructor antes de que los aprendices sientan fatiga?

**RTA:** Sí, es un requisito innovador y de alto valor estratégico para el proyecto. Se requiere que la plataforma integre un componente de Inteligencia Artificial enfocado en la modelación predictiva y el análisis de series de tiempo. El sistema no debe limitarse a ser un receptor pasivo de datos; debe ser capaz de adelantarse en el tiempo cronológico y proyectar matemáticamente el comportamiento de las variables físicas y químicas del ambiente de formación. Esto con el fin de generar alertas predictivas y sugerencias de acciones de mitigación tempranas, permitiendo que el instructor intervenga el espacio antes de que los niveles de CO₂ generen fatiga cognitiva o afecten el rendimiento académico de los aprendices.

1. Si un usuario intenta ingresar a la plataforma y digita la contraseña de forma incorrecta más de 3 veces seguidas, ¿le gustaría que el sistema bloquee esa cuenta automáticamente por unos minutos para evitar que personas extrañas intenten adivinar la clave?

**RTA:** No se considera viable ni requerida la implementación de una política restrictiva de bloqueo automatizado de cuentas por reintentos fallidos en esta fase del software. Dado el contexto operativo del CEFA, donde múltiples instructores y funcionarios interactúan en el día a día académico (muchas veces desde computadores compartidos o entornos de afán), un bloqueo estricto por tres intentos erróneos podría generar parálisis en el acceso al Dashboard de monitoreo, afectando la supervisión continua de la calidad del aire. En su lugar, el sistema mantendrá el acceso abierto pero registrará los intentos fallidos de manera silenciosa en el Log de Auditoría para la supervisión posterior de seguridad.

1. Para las pantallas de configuración global y control de usuarios, ¿prefiere que solo se pueda ingresar si el computador está conectado al internet oficial del centro de formación (SENA CEFA), o se debe permitir el acceso desde cualquier internet externo o datos móviles?

**RTA:** Sí, el sistema debe garantizar una disponibilidad global y permitir el acceso a los módulos de administración y configuración desde cualquier red de internet externa o conexiones de datos móviles. No se debe parametrizar una restricción perimetral exclusiva orientada a la IP local del centro de formación. Esto asegurará que el Administrador y los encargados de Seguridad y Salud en el Trabajo (EHS) puedan supervisar la plataforma, gestionar las cuentas de usuario y mitigar contingencias ambientales en tiempo real de manera remota, incluso estando fuera de las instalaciones físicas del CEFA.

1. Dentro del módulo de configuración, además de los umbrales de alerta, ¿qué otras funciones de gestión de los nodos IoT (dispositivos ESP32) debería tener disponibles el Administrador?

**RTA:** El módulo de configuración debe conservar todas las funciones con las que ya cuenta (parametrización global de umbrales de alerta, gestión de usuarios y demás) y sumar la gestión de nodos IoT. El Administrador debe poder ver el listado completo de nodos, diferenciando los nodos registrados (ya vinculados a un ambiente y a un punto del mapa) de los no registrados (nodos que se conectaron al sistema pero aún no tienen ubicación asignada), y editar de cada uno su ubicación, su nombre y su categoría. Cada nodo debe incluir además los campos de latitud y longitud.

1. Actualmente la pantalla de configuración concentra mucha información en un solo lugar. ¿Cómo prefiere que se organice para que sea clara y ágil de usar?

**RTA:** La pantalla de configuración no debe mostrar todo el contenido al mismo tiempo. Se requiere reestructurarla en secciones o pestañas independientes (por ejemplo: umbrales, nodos IoT, ambientes e instructores, y mapa), de modo que cada una cargue únicamente su propia información. Así se evita sobrecargar la vista y se permite que el Administrador y el EHS encuentren rápidamente lo que necesitan, sin recargar toda la pantalla al cambiar de sección.

1. ¿Desea poder asignar desde la plataforma qué instructor está encargado de cada ambiente de formación?

**RTA:** Sí. El Administrador debe asignar los ambientes de formación y los instructores encargados de cada uno de ellos. Esta asignación le permite saber en qué ambiente y bajo la responsabilidad de qué instructor se encuentra cada espacio monitoreado, y es la base para que el instructor consulte y confirme su ambiente al ingresar. Es un registro operativo independiente del historial de aforo, que se mantiene anónimo.

1. ¿Cómo debe registrarse el mapa digital del CEFA "La Angostura" en la plataforma?

**RTA:** El mapa no delimita ni restringe zonas del CEFA. Cada nodo IoT reporta su ubicación mediante las coordenadas de latitud y longitud que entrega el propio ESP32, y a partir de esas coordenadas el Administrador o el EHS lo registran en el mapa asignándole un nombre al lugar y una categoría (por ejemplo, agrícola o administrativa). El Administrador y el EHS también pueden crear y gestionar las categorías disponibles.

1. Registrar cada punto del mapa a partir de sus coordenadas implica un trabajo manual y un mayor tiempo de dedicación del Administrador. ¿Está de acuerdo en asumirlo?

**RTA:** Sí. Se acepta sacrificar tiempo del Administrador a cambio de contar con una ubicación exacta de cada nodo sobre el plano del CEFA. Tanto el Administrador como el EHS podrán registrar los puntos de los nodos IoT de acuerdo con las coordenadas que entrega el ESP32, asignándoles nombre y categoría, lo que además reparte la carga de trabajo entre ambos perfiles.

1. ¿Cómo debe relacionarse cada punto del mapa con el dispositivo físico ESP32 instalado en el ambiente?

**RTA:** Cada punto del mapa debe corresponder a un único nodo ESP32, identificado por su identificador único y su token de dispositivo. Cuando un nodo se conecta por primera vez queda como "no registrado" hasta que el Administrador o el EHS le asignen nombre, categoría y coordenadas. Si más adelante el sistema detecta que las coordenadas reportadas por un nodo ya registrado cambiaron, debe mostrar en el módulo de configuración un botón "Registrar" para que el Administrador o el EHS revisen si ese nodo tuvo una relación anterior con otra ubicación y, si lo confirman, la actualicen; ese cambio queda guardado en un informe de cambios de ubicación. Una vez registrado, el punto en el mapa debe mostrar el estado de semáforo, el valor de las variables ambientales y el estado de conectividad de ese ESP32, para saber exactamente a qué ambiente acudir ante una contingencia.

**Sección B –** **Funcionario**

1. ¿Necesita consultar el comportamiento del aire de días o semanas anteriores para hacer informes de salud ocupacional, o solo le interesa saber lo que está pasando en el momento exacto?

**RTA:** Se requiere el acceso a ambas modalidades. El monitoreo en tiempo real es indispensable para la mitigación inmediata de emergencias sanitarias, mientras que el almacenamiento del historial de datos es obligatorio para sustentar y estructurar los informes de riesgo epidemiológico ante las directivas del centro.

1. ¿Le sería de utilidad tener un botón para descargar el historial del estado del aire en un archivo de Excel o un reporte en PDF para presentarlo a los comités o entes reguladores?

RTA: Sí, es una función de alta prioridad. Disponer de herramientas nativas para la exportación y descarga de datos históricos en formatos estándar (Excel y PDF) es fundamental para consolidar las evidencias físicas requeridas en las auditorías de salud ocupacional.

1. Al descargar los históricos de calidad del aire en archivos de Excel o PDF para los comités reguladores, ¿desea que estos documentos cuenten con una protección digital que impida que sus datos sean modificados o alterados manualmente después de ser generados por el sistema?

RTA: Sí, el sistema debe garantizar la integridad y el no repudio de la información. Los reportes generados deben incluir un mecanismo de protección o bloqueo digital que impida cualquier manipulación o alteración manual posterior, asegurando la validez legal del documento ante los comités de control.

1. Cuando un ambiente de formación se encierre y el aire se vuelva pesado (superando los niveles saludables), ¿cómo prefiere que el sistema le avise? ¿Es suficiente con un mensaje llamativo en la pantalla web, o le gustaría que el sistema envíe un correo electrónico automático al encargado del área o a su oficina?

RTA: Es necesario un sistema de notificación dual. Se requiere una alerta visual persistente y de alto contraste en el panel web para el control inmediato en pantalla, complementada con el envío automatizado de un correo electrónico institucional para asegurar la notificación fuera de la oficina.

1. ¿Qué nivel de prioridad debería tener una alerta por mala calidad del aire?

Baja – Media – Alta – Critica

RTA: La prioridad debe ser catalogada como Crítica. Superar los límites de contaminación implica un riesgo directo sobre la salud y la capacidad cognitiva de los aprendices, exigiendo la ejecución inmediata de protocolos de evacuación o ventilación forzada en el ambiente afectado.

1. ¿Qué acciones debería recomendar automáticamente el sistema cuando se detecten niveles altos de CO₂?

RTA: El sistema debe emitir recomendaciones operativas inmediatas y de fácil comprensión, tales como: la apertura total de ventanas y puertas para habilitar ventilación cruzada, la activación de sistemas mecánicos de extracción de aire y la evacuación preventiva del ambiente si la alerta roja persiste.

1. ¿Desea conocer cuántas personas se encuentran ocupando cada ambiente de formación al momento de registrarse la medición del aire para cruzar esa información con el estado de encierro?

RTA: Sí, conocer la cantidad exacta de ocupantes es un dato analítico clave. Permitirá cruzar las métricas de gases con el aforo del espacio para identificar científicamente la velocidad de saturación del aire y evaluar el factor de riesgo de sobrepoblación en cada bloque del CEFA.

1. ¿Durante cuánto tiempo requiere que el sistema conserve guardados los datos e históricos anteriores del aire (por ejemplo: ¿tres meses, un año o de forma permanente) para la elaboración de sus informes de salud ocupacional?

RTA: Se requiere que los registros históricos se conserven guardados en la base de datos por un periodo mínimo de un (1) año. Este tiempo cubre la vigencia del año académico completo y asegura el insumo necesario para los balances y auditorías anuales de salud ocupacional del centro.

1. Para los mensajes de recomendación que mostrará el sistema cuando el aire se ponga pesado, ¿el área de EHS nos entregará un listado de acciones o protocolos ya aprobados por el centro, o prefiere que el equipo de desarrollo proponga estas sugerencias desde cero?

RTA: El área de Seguridad y Salud en el Trabajo (EHS) entregará al equipo de desarrollo el listado de protocolos institucionales y normativas vigentes ya aprobados por el centro. El software deberá incorporar y desplegar textualmente estas instrucciones oficiales para mantener la alineación reglamentaria del SENA.

1. Para que el sistema no solo muestre gráficos en pantalla, sino que sea capaz de anticiparse a los problemas, ¿le gustaría que la plataforma cuente con un asistente inteligente (Inteligencia Artificial) que aprenda automáticamente del comportamiento del salón y prediga con anticipación en qué momento exacto el aire se volverá pesado, sugiriendo la acción exacta que debe tomar el instructor antes de que los aprendices sientan fatiga?

RTA: Sí, es una función de alta prioridad. Incorporar analítica predictiva permitirá que el sistema anticipe matemáticamente el deterioro del aire antes de que ocurra, emitiendo recomendaciones preventivas oportunas al instructor para mitigar la fatiga cognitiva de los aprendices y optimizar la salud ocupacional en el CEFA.

1. Como EHS, ¿necesita poder ver y modificar los nodos IoT registrados y no registrados del centro, o solo consultar las mediciones?

**RTA:** Sí, el EHS debe poder ver los nodos IoT registrados y no registrados, cada uno identificado por su token de dispositivo, y editar su ubicación, su nombre y su categoría, al igual que el Administrador. Si el sistema detecta que un nodo ya registrado cambió de coordenadas, debe mostrarle al EHS y al Administrador un botón "Registrar" en el módulo de configuración para revisar si tuvo una relación anterior con otra ubicación y confirmar el cambio, el cual queda guardado en un informe de cambios de ubicación.

1. ¿Desea que el EHS pueda registrar en el mapa los puntos de los nodos IoT de acuerdo con sus coordenadas?

**RTA:** Sí. El EHS podrá registrar los puntos de los nodos IoT en el mapa a partir de las coordenadas de latitud y longitud que entrega el ESP32, asignándoles nombre y categoría, con los mismos permisos que el Administrador para esta función.

**Sección C – Instructor**

1. Como instructor, ¿le gustaría ingresar a una pantalla simple donde el sistema reconozca automáticamente en qué ambiente de formación va a realizar la sesión de clase o se encuentra instalado el dispositivo?

RTA: Sí. El sistema debe mostrar al instructor el ambiente de formación que le asignó el Administrador y asociarlo automáticamente con el dispositivo físico instalado en él, evitando que el docente pierda tiempo en configuraciones manuales antes de iniciar la sesión académica. El instructor solo debe confirmar su estadía en ese ambiente.

1. ¿Le gustaría que la interfaz use colores tipo semáforo (Verde = Todo bien, Amarillo = Precaución, ¿Rojo = Ventilar de inmediato) para que entienda la calidad del aire de un solo vistazo en el aula sin interpretar datos químicos complejos?

RTA: Sí, definitivamente. Es indispensable el uso de la escala visual del semáforo (🟢, 🟡, 🔴) para identificar al instante el estado del entorno sin interrumpir el desarrollo pedagógico de la clase.

1. ¿Es necesario que los datos de los gráficos (CO₂, temperatura, humedad) cambien de forma automática e inmediata en la pantalla, o le parece bien que se actualicen, por ejemplo, ¿cada minuto?

RTA: Es aceptable una actualización fija cada minuto. Este intervalo es óptimo para reflejar fielmente las variaciones reales del aire sin saturar el procesamiento del sistema.

1. ¿Le gustaría recibir recomendaciones automáticas cuando la calidad del aire sea inadecuada?

RTA: Sí. El sistema debe mostrar sugerencias claras y rápidas (como abrir ventanas o encender extractores) para actuar de inmediato cuando el aire se ponga pesado.

1. Al iniciar la sesión en el ambiente de formación, ¿le parece bien ingresar manualmente a la plataforma el número de aprendices presentes en ese momento para que el sistema pueda cruzar el dato de la cantidad de personas con el estado del aire?

RTA: Sí, me parece correcto. El instructor digitará a mano la cantidad de alumnos presentes al comenzar la jornada como una acción sencilla dentro de su rutina de inicio. Este registro se realizará en el mismo momento en que confirme su estadía en el ambiente asignado y, si cambia de ambiente, la cantidad de estudiantes se informará junto con la notificación al Administrador.

1. Además de los avisos y colores en la plataforma web, ¿considera necesario que el aparato físico instalado en el ambiente de formación cuente con una luz propia (como un indicador LED) que cambie de color para avisar directamente a los aprendices e instructores que es momento de ventilar?

RTA: Sí, es de gran utilidad. Un indicador LED físico in situ garantiza que todos los presentes en el aula se enteren de la alerta sin necesidad de estar mirando la pantalla de un computador.

1. Debido a que el instructor ingresará de manera manual el número de personas en el salón, ¿el registro histórico de la cantidad de alumnos debe quedar guardado con el nombre del instructor que dictaba la clase en ese momento, o prefiere que se guarde de forma anónima y separada, utilizándose únicamente para el análisis de los informes de salud ocupacional?

RTA: Se prefiere que se guarde de forma anónima y separada. El dato de aforo debe desvincularse del nombre del docente y procesarse exclusivamente como insumo estadístico para los análisis ambientales de salud ocupacional del centro.

1. ¿Le parece adecuado que, al ingresar, la plataforma le muestre el ambiente de formación que le asignó el Administrador y usted confirme su estadía en él?

**RTA:** Sí. El instructor revisa el ambiente de formación asignado, confirma su estadía en ese ambiente y agrega la cantidad de aprendices en formación ese día, todo en un solo paso dentro de su rutina de inicio.

1. Si por alguna razón usted debe dictar la clase en un ambiente distinto al asignado, ¿desea que el sistema avise al Administrador?

**RTA:** Sí. Si sucede un cambio, el instructor lo registra y el sistema envía una notificación al Administrador indicando en qué ambiente estará y cuántos estudiantes habrá en él, para que el Administrador tenga siempre la ubicación real de cada instructor y de su grupo.

**Sección D – Atributos de Calidad (Expectativas de Rendimiento)**

1. Cuando ocurra un cambio drástico en el ambiente de formación (por ejemplo, si abren o cierran todas las ventanas de golpe durante una alerta roja), ¿qué tan rápido espera que ese cambio se refleje visualmente en las gráficas de la pantalla web?

RTA: Prácticamente de inmediato. Se espera que el cambio se refleje visualmente en un tiempo máximo de 2 segundos para asegurar que el instructor y los encargados constaten la recuperación real del aire.

1. ¿Cuál es el tiempo máximo aceptable para que una alerta aparezca en pantalla?

RTA: Máximo 2 segundos. La notificación visual en el panel web debe saltar de manera inmediata tras la detección de niveles críticos para garantizar una evacuación o ventilación oportuna.

1. Si la conexión a internet es inestable, ¿esperaría que el sistema conserve la información y continúe funcionando cuando se restablezca la red?

RTA: Sí. El dispositivo físico debe guardar temporalmente los datos durante la caída de la señal y transmitirlos automáticamente al servidor una vez que retorne el internet, evitando la pérdida del historial.

1. ¿Cuánto tiempo considera aceptable que el sistema esté fuera de servicio en caso de mantenimiento?

RTA: Máximo un par de horas y estrictamente programado en jornadas nocturnas o fines de semana, de modo que nunca se interrumpa el monitoreo ambiental durante las horas de clase.

**REQUISITOS FUNCIONALES**

|  |  |  |
| --- | --- | --- |
| **CODIGO** | **REQUERIMIENTOS** | **DESCRIPCIÓN** |
| **RF-01** | Autenticación de Usuarios | El sistema debe permitir la autenticación de usuarios mediante credenciales válidas. |
| **RF-02** | Control de Acceso Basado en Roles (RBAC) | El sistema debe permitir el control de acceso basado en roles (RBAC), asignando permisos según el tipo de usuario. |
| **RF-03** | Panel de Monitoreo General (Dashboard) | El sistema debe permitir visualizar un panel de monitoreo general (Dashboard) mediante un mapa digital interactivo del CEFA, que muestre en tiempo real el estado de calidad del aire (CO₂, temperatura y humedad, expresado mediante el sistema semáforo) de los ambientes monitoreados, señalando la ubicación del prototipo instalado. La ubicación de cada punto corresponde a las coordenadas (latitud y longitud) registradas para el nodo IoT. |
| **RF-04** | Monitoreo de Variables de Gases | El sistema debe visualizar sobre cada punto del nodo, en el mapa digital interactivo, el valor en tiempo real de las variables de gases (CO₂) medidas por los sensores. Al hacer clic sobre el punto del nodo, el sistema debe abrir una ventana flotante con el detalle de la variable, incluyendo su valor numérico actual y una gráfica histórica de las lecturas registradas en las últimas 24 horas. |
| **RF-05** | Monitoreo de Variables Climáticas | El sistema debe visualizar sobre cada punto del nodo, en el mapa digital interactivo, el valor en tiempo real de las variables climáticas (temperatura y humedad) registradas por los sensores. Al hacer clic sobre el punto del nodo, el sistema debe abrir una ventana flotante con el detalle de la variable, incluyendo su valor numérico actual y una gráfica histórica de las lecturas registradas en las últimas 24 horas. |
| **RF-06** | Indexación de Datos | El sistema debe implementar índices en la base de datos, por nodo, variable y marca de tiempo, con el fin de optimizar el rendimiento de las consultas históricas y de los reportes. Este es un requisito técnico interno de la base de datos y no corresponde a un buscador visible para el usuario final. |
| **RF-07** | Reconocimiento y Registro de Nodos IoT | El sistema debe reconocer automáticamente cada nodo IoT (ESP32) cuando se conecta por primera vez, registrándolo como "no registrado" hasta que el Administrador o el EHS le asignen nombre, categoría y coordenadas. A partir de ese momento, el nodo queda vinculado a un punto del mapa y comienza a transmitir sus lecturas ambientales de forma válida dentro del sistema (véase RF-30 para la validación por token y RF-31 a RF-35 para la gestión completa de nodos). |
| **RF-08** | Parametrización Global de Umbrales de Alerta | El sistema debe permitir parametrizar de forma global los umbrales de alerta de las variables ambientales. Esta función hace parte del módulo de configuración, que además integra la gestión de nodos IoT (RF-31 a RF-43). |
| **RF-09** | Gestión de Usuarios (CRUD) | El sistema debe permitir gestionar usuarios mediante las operaciones de crear, consultar, actualizar y eliminar (CRUD). |
| **RF-10** | Restablecimiento Automatizado de Credenciales | El sistema debe permitir el restablecimiento automatizado de las credenciales de acceso de los usuarios. |
| **RF-11** | Trazabilidad | El sistema debe registrar de forma trazable los eventos generados automáticamente por el propio sistema (lecturas de sensores, cambios de estado del semáforo, alertas emitidas y predicciones generadas), permitiendo reconstruir la secuencia de eventos ambientales y de sistema en el tiempo. Este requisito se diferencia del RF-12, que registra las acciones realizadas manualmente por los usuarios. |
| **RF-12** | Log de Auditoría Interna | El sistema debe registrar en un log de auditoría interna las acciones realizadas manualmente por los usuarios dentro de la plataforma (inicio de sesión, creación, edición o eliminación de registros, cambios de configuración y exportación de reportes, entre otras), indicando el usuario responsable, la acción realizada y la fecha y hora. Este requisito se diferencia del RF-11, que registra eventos automáticos generados por el sistema. |
| **RF-13** | Registro Silencioso de Intentos Fallidos | El sistema debe permitir registrar de forma silenciosa los intentos fallidos de autenticación. |
| **RF-14** | Almacenamiento de Lecturas de Sensores | El sistema debe permitir almacenar continuamente las lecturas de los sensores en la base de datos para su posterior análisis. |
| **RF-15** | Exportación de Reportes Protegidos | El sistema debe permitir exportar reportes en formato Excel y PDF. Los reportes exportados en PDF deben generarse protegidos contra edición (bloqueados para modificación) y deben incluir un mecanismo de verificación de integridad (hash) que permita confirmar que el archivo no ha sido alterado después de su generación. |
| **RF-16** | Sistema de Notificación Dual | El sistema debe permitir enviar notificaciones mediante dos canales activados de forma simultánea ante una condición de alerta: (1) una alerta visual desplegada en la plataforma web, y (2) un correo electrónico institucional dirigido al usuario correspondiente. |
| **RF-17** | Despliegue de Recomendaciones | El sistema debe permitir desplegar recomendaciones de acuerdo con las condiciones ambientales detectadas. |
| **RF-18** | Recomendaciones del Manual de Contingencia | El sistema debe permitir al EHS gestionar mediante operaciones de crear, editar y eliminar (CRUD) las recomendaciones del manual de contingencia, organizadas por categoría de riesgo predefinida (por ejemplo, exceso de CO₂, temperatura alta o humedad alta), dado que es el rol con el conocimiento técnico en Seguridad y Salud en el Trabajo para definir dichas recomendaciones. Cada recomendación debe registrarse con un título y una descripción en texto plano. El Administrador y el Instructor solo pueden consultar estas recomendaciones, las cuales se despliegan automáticamente de acuerdo con las condiciones ambientales detectadas (RF-17). |
| **RF-19** | Ingreso Manual de Ocupantes (Aforo) | El sistema debe permitir registrar manualmente el número de ocupantes (aforo) de un espacio. El registro se realiza cuando el instructor confirma su estadía en el ambiente asignado (RF-38). |
| **RF-20** | Anonimización del Historial de Aforo | El sistema debe permitir anonimizar el historial de aforo para proteger la privacidad de los datos. La asignación de instructores a ambientes (RF-37) es un registro operativo independiente y no debe vincularse al historial de aforo. |
| **RF-21** | Monitor de Conectividad del Hardware (Keep-Alive) | El sistema debe permitir monitorear continuamente la conectividad del hardware mediante el mecanismo Keep-Alive. |
| **RF-22** | Módulo Predictivo con Inteligencia Artificial | El sistema debe permitir ejecutar un módulo predictivo basado en Inteligencia Artificial. |
| **RF-23** | Procesamiento de Datos para Análisis Predictivo | El sistema debe permitir procesar los datos recopilados para realizar análisis predictivos, mediante técnicas de regresión y análisis de series de tiempo aplicadas sobre el historial de lecturas ambientales. Para generar una predicción confiable, cada ambiente debe contar con un mínimo de 100 lecturas históricas continuas (equivalentes aproximadamente a 24 horas de monitoreo, dado el ciclo de actualización de un minuto definido en RNF-04). Mientras un ambiente no alcance este mínimo, el sistema debe marcarlo como "En aprendizaje" y abstenerse de publicar una predicción, evitando presentar un resultado no confiable como si fuera válido. |
| **RF-24** | Análisis Predictivo de Variables Ambientales | El sistema debe permitir realizar análisis predictivos de las variables ambientales monitoreadas. |
| **RF-25** | Predicción de Niveles de CO₂ | El sistema debe permitir predecir los niveles futuros de CO₂ con base en los datos históricos y actuales. |
| **RF-26** | Detección Predictiva de Riesgo Ambiental | El sistema debe permitir detectar de forma predictiva situaciones de riesgo ambiental. |
| **RF-27** | Generación de Alertas Predictivas | El sistema debe permitir generar alertas predictivas cuando se identifiquen condiciones de riesgo. |
| **RF-28** | Visualización de Predicciones | El sistema debe permitir visualizar las predicciones generadas por el módulo de Inteligencia Artificial mediante gráficos e indicadores. |
| **RF-29** | Visualización de Variables Ambientales en el Mapa | El sistema debe permitir visualizar en el mapa digital interactivo del CEFA el valor específico de cada variable ambiental (CO₂, temperatura o humedad) por ambiente de formación, permitiendo alternar entre ellas. |
| **RF-30** | Autenticidad del Origen de las Lecturas IoT | El sistema debe validar la autenticidad de cada nodo IoT (ESP32) antes de aceptar sus lecturas, mediante un identificador único y una clave de dispositivo (token) configurados en el firmware y verificados por el backend en cada transmisión. Las lecturas que no incluyan un token válido y reconocido deben ser rechazadas y registradas en el log de auditoría como un intento de transmisión no autorizada, evitando que un dispositivo no autorizado suplante a un nodo legítimo y envíe datos falsos al sistema. |
| **RF-31** | Gestión de Nodos IoT Registrados y No Registrados | El sistema debe permitir al Administrador y al EHS visualizar, dentro del módulo de configuración, el listado de los nodos IoT (ESP32), diferenciando los nodos registrados de los no registrados. |
| **RF-32** | Edición de Nodos IoT | El sistema debe permitir al Administrador y al EHS editar la ubicación, el nombre y la categoría de un nodo IoT, identificado siempre por su token de dispositivo (RF-30). |
| **RF-33** | Coordenadas Geográficas del Nodo | El sistema debe incluir en el registro de cada nodo IoT los campos de latitud y longitud reportados por el propio ESP32, validando que sean valores numéricos con formato de coordenadas válido. |
| **RF-34** | Registro de Nodos en el Mapa por Coordenadas | El sistema debe permitir al Administrador y al EHS registrar en el mapa digital el punto de cada nodo IoT a partir de las coordenadas de latitud y longitud que reporta el propio ESP32, asignándole un nombre de lugar y una categoría (por ejemplo, agrícola o administrativa). |
| **RF-35** | Gestión de Categorías de Ubicación | El sistema debe permitir al Administrador y al EHS crear, editar y asignar las categorías con las que se clasifica la ubicación de cada nodo IoT en el mapa del CEFA "La Angostura" (por ejemplo, agrícola, administrativa o académica); el mapa no delimita ni restringe las zonas donde puede registrarse un nodo, ya que estos pueden instalarse en cualquier parte del centro de formación. |
| **RF-36** | Relación entre Mapa y Nodo ESP32 | El sistema debe relacionar cada punto del mapa con un único nodo ESP32, identificado por su identificador único (RF-30), de modo que el punto muestre el estado de semáforo, el valor de las variables ambientales y el estado de conectividad (RF-21) del dispositivo que le corresponde. |
| **RF-37** | Asignación de Ambientes e Instructores | El sistema debe permitir al Administrador asignar los ambientes de formación y los instructores encargados de cada ambiente, así como editar dicha asignación en cualquier momento. Si un instructor aún no tiene un ambiente asignado, el sistema debe mostrarle un mensaje indicando que no cuenta con una asignación vigente y que debe comunicarse con el Administrador. |
| **RF-38** | Confirmación de Estadía y Aforo Diario | El sistema debe permitir al instructor consultar el ambiente de formación que le fue asignado, confirmar su estadía en él y registrar la cantidad de aprendices en formación ese día (RF-19). |
| **RF-39** | Notificación de Cambio de Ambiente | El sistema debe enviar una notificación al Administrador cuando un instructor cambie de ambiente respecto al asignado, indicando el ambiente en el que estará y la cantidad de estudiantes que estarán en él. |
| **RF-40** | Detección de Cambio de Ubicación de Nodo IoT | El sistema debe detectar cuando las coordenadas reportadas por un nodo IoT ya registrado difieren de las almacenadas y, en ese caso, mostrar en el módulo de configuración un botón "Registrar", visible para el Administrador y el EHS. |
| **RF-41** | Confirmación y Registro de Cambios de Ubicación | Al presionar el botón "Registrar" (RF-40), el sistema debe mostrarle al Administrador o al EHS si el nodo tuvo una relación anterior con otra ubicación y permitirle confirmar o descartar el cambio de coordenadas. Todo cambio de ubicación confirmado debe quedar registrado y disponible para consulta y descarga en el submódulo "Reporte de Nodos" (RF-43), indicando el nodo, la ubicación anterior, la nueva ubicación, quién realizó el cambio y la fecha. |
| **RF-42** | Aplicación Móvil Nativa para Android | El sistema debe contar con una aplicación móvil nativa para Android que replique la funcionalidad completa de la plataforma web (el mismo sistema, en formato de aplicación), disponible para todos los roles de acuerdo con sus permisos; no se contempla el desarrollo de una versión nativa para iOS. La aplicación debe ser compatible como mínimo con Android 8.0 (API 26) en adelante, y su distribución se realizará mediante archivo APK de instalación directa, sin contemplar publicación en Google Play Store. |
| **RF-43** | Submódulo de Reporte de Nodos | Dentro del módulo de configuración, el sistema debe incluir un submódulo "Reporte de Nodos" donde el Administrador y el EHS puedan consultar y descargar, en Excel y/o PDF, el listado de nodos IoT registrados y no registrados junto con el historial de cambios de ubicación confirmados (RF-41), incluyendo el nodo, la ubicación anterior, la nueva ubicación, quién realizó el cambio y la fecha. |
| **RF-44** | Historial de Lecturas con Filtros | El sistema debe permitir consultar el historial de lecturas ambientales mediante una vista dedicada de historial, con filtros por rango de fechas, por ambiente (nodo) y por variable (CO₂, temperatura o humedad), presentando los resultados en una gráfica de línea temporal. Dicho historial debe poder exportarse conforme a lo definido en RF-15. |
| **RF-45** | Exportación del Manual de Contingencia en PDF | El sistema debe permitir exportar el manual de contingencia completo, con todas las recomendaciones vigentes organizadas por categoría de riesgo (RF-18), en formato PDF. El documento generado debe incluir una marca de agua con el logotipo y el nombre "AirSense CEFA" visible en cada página. |

**REQUISITOS NO FUNCIONALES**

|  |  |  |
| --- | --- | --- |
| **CODIGO** | **CATEGORIA** | **DESCRIPCIÓN** |
| **RNF-01** | Seguridad de la Información (Sesión) | El sistema debe cerrar automáticamente la sesión del usuario tras un periodo de inactividad continua, diferenciado por rol: 10 minutos para los roles de Administrador y EHS, dado que administran configuración sensible y credenciales institucionales; y 30 minutos para el rol Instructor, considerando que permanece dentro del aula sin interactuar constantemente con la plataforma durante el desarrollo de la clase. En ambos casos, el sistema debe destruir el token de autenticación y redirigir al inicio de sesión. |
| **RNF-02** | Disponibilidad | Los módulos críticos de administración y configuración deben estar disponibles mediante redes de internet externas y conexiones de datos móviles, sin depender exclusivamente de la red local del CEFA. |
| **RNF-03** | Usabilidad (UI/UX – Semáforo) | La interfaz debe utilizar un sistema visual tipo semáforo para facilitar la interpretación de la calidad del aire: verde para condiciones óptimas, amarillo para precaución y rojo para condiciones críticas. |
| **RNF-04** | Eficiencia (Rendimiento de Transmisión) | Los valores y gráficos del panel web deben actualizarse automáticamente en un intervalo máximo de un minuto, evitando una carga innecesaria sobre el procesamiento del sistema. |
| **RNF-05** | Rendimiento (Latencia en Tiempo Real) | La transmisión de una alerta y su actualización visual en la plataforma no debe tardar más de 2 segundos después de detectarse un cambio crítico. |
| **RNF-06** | Resiliencia IoT (Persistencia Local) | El dispositivo IoT basado en ESP32 debe almacenar temporalmente las lecturas cuando se pierda la conexión Wi-Fi y sincronizarlas automáticamente cuando se restablezca la conexión. |
| **RNF-07** | Mantenibilidad | El sistema debe soportar una ventana de mantenimiento programada con una duración máxima de dos horas, notificada a los usuarios mediante un aviso visible en la plataforma con al menos 24 horas de anticipación. Por política institucional, estas ventanas de mantenimiento se programan preferiblemente en horarios nocturnos o fines de semana. |
| **RNF-08** | Portabilidad (Diseño Responsivo) | La plataforma web debe implementar diseño responsivo para garantizar su correcta visualización y funcionamiento en computadores, tablets y teléfonos inteligentes. |
| **RNF-09** | Persistencia de Almacenamiento (Base de Datos) | La base de datos debe estar dimensionada y optimizada para conservar y permitir consultar los registros históricos ambientales durante un período mínimo de un año académico. |
| **RNF-10** | Restricción de Hardware (Actuador Local) | El dispositivo físico instalado en los ambientes debe incorporar un indicador LED multicolor que refleje el estado de alerta mostrado por el semáforo de la plataforma web. |
| **RNF-11** | Capacidad y Concurrencia del Sistema | La plataforma debe soportar como mínimo la operación simultánea de 15 nodos IoT transmitiendo datos en paralelo y 30 usuarios concurrentes autenticados, sin degradar los tiempos de actualización (RNF-04) ni de respuesta ante alertas (RNF-05) definidos para el sistema. Esta cifra corresponde al total estimado de ambientes de formación e instructores/funcionarios activos del CEFA "La Angostura", y debe ajustarse si el centro amplía su cobertura de monitoreo. |
| **RNF-12** | Usabilidad (Organización del Módulo de Configuración) | El módulo de configuración debe estructurarse en secciones o pestañas independientes (umbrales, nodos IoT, ambientes e instructores, mapa y reporte de nodos), cargando únicamente el contenido de la sección activa para evitar la sobrecarga de información en una sola pantalla. |
| **RNF-13** | Consistencia de Datos Geográficos (Mapa – Nodo) | Cada nodo IoT debe estar asociado a un único punto del mapa y a un único par de coordenadas. Todo cambio de ubicación debe reflejarse en el mapa a más tardar en la siguiente actualización del panel definida en RNF-04. |
| **RNF-14** | Seguridad (Acceso a la Gestión de Nodos) | La gestión de nodos IoT y el registro de puntos en el mapa solo deben estar disponibles para los roles Administrador y EHS, y cada cambio realizado debe quedar registrado en el log de auditoría. |