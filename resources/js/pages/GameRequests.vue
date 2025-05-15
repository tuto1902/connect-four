<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Command, Play } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref, watch } from 'vue';
import { useStreamerbot } from '@streamerbot/vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Game Requests',
        href: '/games',
    },
];

interface GameRequest {
  id: number;
  user: string;
  is_winner: boolean;
  played_at: string;
  created_at: string;
  updated_at: string;
}

const { client, status, data, connect, disconnect } = useStreamerbot({
    subscribe: { Command: ['Triggered'] },
    host: '192.168.100.14'
});

const userName = ref('');

function hasResults(isWinner: any): boolean {
    return isWinner !== null && isWinner !== undefined;
}

function gameResult(isWinner: any): string {
    if (isWinner === 1) {
        return 'Won';
    } else if (isWinner === 0) {
        return 'Lost';
    }

    return '';
}

defineProps<{ gameRequests: GameRequest[] }>();

watch(data, (event) => {
    if (event.hasOwnProperty('data')) {
        if (event.data.command == '!g' || event.data.command == '!game') {
            userName.value = event.data.user.display;
            router.post('/games?from=list', { user: userName.value })
        }
    }
});

onMounted(() => {
    connect();
});

onUnmounted(() => {
    disconnect();
})


function play(game_request_id:number) {
    router.get(`/play/${game_request_id}`);
}
</script>

<template>
    <Head title="Game Requests" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table>
                <TableCaption>A list of game requests</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>User Name</TableHead>
                        <TableHead>Requested At</TableHead>
                        <TableHead>Result</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="gameRequest in gameRequests" :key="gameRequest.id">
                        <TableCell>{{ gameRequest.user }}</TableCell>
                        <TableCell>{{ gameRequest.created_at }}</TableCell>
                        <TableCell>{{ gameResult(gameRequest.is_winner) }}</TableCell>
                        <TableCell>
                            <Button v-if="!hasResults(gameRequest.is_winner)" @click="play(gameRequest.id)">
                                <Play class="size-4 mr-2" /> Play
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
