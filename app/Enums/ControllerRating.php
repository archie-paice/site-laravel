<?php

namespace App\Enums;

enum ControllerRating: int
{
    case INA = -1;
    case SUS = 0;
    case OBS = 1;
    case S1 = 2;
    case S2 = 3;
    case S3 = 4;
    case C1 = 5;
    case C2 = 6;
    case C3 = 7;
    case I1 = 8;
    case I2 = 9;
    case I3 = 10;
    case SUP = 11;
    case ADM = 12;

    public function mapToString(): string
    {
        return match ($this) {
            self::INA => 'INA',
            self::SUS => 'SUS',
            self::OBS => 'OBS',
            self::S1 => 'S1',
            self::S2 => 'S2',
            self::S3 => 'S3',
            self::C1 => 'C1',
            self::C2 => 'C2',
            self::C3 => 'C3',
            self::I1 => 'I1',
            self::I2 => 'I2',
            self::I3 => 'I3',
            self::SUP => 'SUP',
            self::ADM => 'ADM',
        };
    }
}

/*
ID	Short Text	Long Text
-1	INA	Inactive
0	SUS	Suspended
1	OBS	Pilot/Observer
2	S1	Tower Trainee
3	S2	Tower Controller
4	S3	TMA Controller
5	C1	Enroute Controller
6	C2	Senior Controller
7	C3	Senior Controller
8	I1	Instructor
9	I2	Senior Instructor
10	I3	Senior Instructor
11	SUP	Supervisor
12	ADM	Administrator*/
