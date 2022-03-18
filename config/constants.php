<?php

namespace App;

class Privileges {
    const ADMIN_HIERARCHY = ['Admin'];
    const DEAN_HIERARCHY = ['Admin', 'Decano'];
    const COORDINATOR_HIERARCHY = ['Admin', 'Decano', 'Coordinador'];
    const PROFESSOR_HIERARCHY = ['Admin', 'Decano', 'Coordinador', 'Profesor'];
    const SECRETARY_HIERARCHY = ['Admin', 'Decano', 'Coordinador', 'Profesor', 'Secretaria'];
}

const DEFAULT_PAGE_SIZE = 10;
