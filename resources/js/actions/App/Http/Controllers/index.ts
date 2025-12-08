import DersController from './DersController'
import OgretmenController from './OgretmenController'
import MekanController from './MekanController'
import OgrenciGrubuController from './OgrenciGrubuController'
import ZamanDilimController from './ZamanDilimController'
import DersMekanGeresinimController from './DersMekanGeresinimController'
import GrupDersController from './GrupDersController'
import OgretmenDersController from './OgretmenDersController'
import OgretmenMusaitlikController from './OgretmenMusaitlikController'
import GrupKisitlamaController from './GrupKisitlamaController'
import ProgramOlusturController from './ProgramOlusturController'
import TimetableSettingController from './TimetableSettingController'
import Settings from './Settings'

const Controllers = {
    DersController: Object.assign(DersController, DersController),
    OgretmenController: Object.assign(OgretmenController, OgretmenController),
    MekanController: Object.assign(MekanController, MekanController),
    OgrenciGrubuController: Object.assign(OgrenciGrubuController, OgrenciGrubuController),
    ZamanDilimController: Object.assign(ZamanDilimController, ZamanDilimController),
    DersMekanGeresinimController: Object.assign(DersMekanGeresinimController, DersMekanGeresinimController),
    GrupDersController: Object.assign(GrupDersController, GrupDersController),
    OgretmenDersController: Object.assign(OgretmenDersController, OgretmenDersController),
    OgretmenMusaitlikController: Object.assign(OgretmenMusaitlikController, OgretmenMusaitlikController),
    GrupKisitlamaController: Object.assign(GrupKisitlamaController, GrupKisitlamaController),
    ProgramOlusturController: Object.assign(ProgramOlusturController, ProgramOlusturController),
    TimetableSettingController: Object.assign(TimetableSettingController, TimetableSettingController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers