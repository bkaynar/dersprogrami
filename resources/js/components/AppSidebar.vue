<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import derslerRoute from '@/routes/dersler';
import ogretmenlerRoute from '@/routes/ogretmenler';
import mekanlarRoute from '@/routes/mekanlar';
import ogrenciGruplariRoute from '@/routes/ogrenci-gruplari';
import zamanDilimleriRoute from '@/routes/zaman-dilimleri';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, LayoutGrid, Users, Building2, UsersRound, Clock, MapPin, BookCheck, UserCheck, ClockAlert, ShieldAlert, CalendarDays, Settings } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Dersler',
        href: derslerRoute.index().url,
        icon: BookOpen,
    },
    {
        title: 'Öğretmenler',
        href: ogretmenlerRoute.index().url,
        icon: Users,
    },
    {
        title: 'Mekanlar',
        href: mekanlarRoute.index().url,
        icon: Building2,
    },
    {
        title: 'Öğrenci Grupları',
        href: ogrenciGruplariRoute.index().url,
        icon: UsersRound,
    },
    {
        title: 'Zaman Dilimleri',
        href: zamanDilimleriRoute.index().url,
        icon: Clock,
    },
    {
        title: 'Ders Mekan Gereksinimleri',
        href: '/ders-mekan-gereksinimleri',
        icon: MapPin,
    },
    {
        title: 'Grup Dersleri',
        href: '/grup-dersleri',
        icon: BookCheck,
    },
    {
        title: 'Öğretmen Dersleri',
        href: '/ogretmen-dersleri',
        icon: UserCheck,
    },
    {
        title: 'Öğretmen Müsaitliği',
        href: '/ogretmen-musaitlik',
        icon: ClockAlert,
    },
    {
        title: 'Grup Kısıtlamaları',
        href: '/grup-kisitlamalari',
        icon: ShieldAlert,
    },
    {
        title: 'Program Oluştur',
        href: '/program-olustur',
        icon: CalendarDays,
    },
    {
        title: 'Program Ayarları',
        href: '/timetable-settings',
        icon: Settings,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>

            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
