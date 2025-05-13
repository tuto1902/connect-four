<script setup lang="ts">
import GameLayout from '@/layouts/GameLayout.vue';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useStreamerbot } from '@streamerbot/vue';

const gameOver = ref(false);
const turn = ref('player1');

const userName = ref('');

const COLUMNS = 7;
const ROWS = 6;

const { client, status, data, connect, disconnect } = useStreamerbot({
    subscribe: { Command: ['Triggered'] },
    host: '192.168.100.14'
});

const props = defineProps<{ user:string }>();

watch(data, (event) => {
    if (event.hasOwnProperty('data')) {
        if (event.data.command == '!play' && event.data.user.display == props.user && turn.value == 'player2') {
            const match = event.data.message.match(/^[1-7]$/);
            if (match) {
                const column = parseInt(match[0]);
                takeTurn(column-1);
            }
        }
    }
});

onMounted(() => {
    connect()
});

onUnmounted(() => {
    disconnect();
})

const board = computed(() => {
    let board: number[][] = []
    for (let row = 0; row < 6; row++) {
        board.push([])
    }
    board.forEach(row => {
        for (let column = 0; column < 7; column++) {
            row.push(0);
        }
    })
    return board;
})


function isColumnFull(column:number) {
    return board.value[0][column] != 0;
}

function findEmptyRowInColumn(column:number): number {
    for (let row = 5; row >= 0; row--) {
        if (board.value[row][column] == 0) {
            return row
        }
    }
    return -1;
}

function isWinningMove(color:number) {
    // Check all rows
    for (let c = 0; c < COLUMNS - 3; c++) {
        for (let r = 0; r < ROWS; r++) {
            if (
                board.value[r][c] == color &&
                board.value[r][c + 1] == color &&
                board.value[r][c + 2] == color &&
                board.value[r][c + 3] == color
            ) {
                return true
            }
        }
    }
    // Check all columns
    for (let c = 0; c < COLUMNS; c++) {
        for (let r = ROWS - 1; r >= ROWS - 3; r--) {
            if (
                board.value[r][c] == color &&
                board.value[r - 1][c] == color &&
                board.value[r - 2][c] == color &&
                board.value[r - 3][c] == color
            ) {
                return true
            }
        }
    }
    // Check for all upward diagonals
    for (let c = 0; c < COLUMNS - 3; c++) {
        for (let r = ROWS - 1; r >= ROWS - 3; r--) {
            if (
                board.value[r][c] == color &&
                board.value[r - 1][c + 1] == color &&
                board.value[r - 2][c + 2] == color &&
                board.value[r - 3][c + 3] == color
            ) {
                return true
            }
        }
    }
    // Check for all downward diagonals
    for (let c = 0; c < COLUMNS - 3; c++) {
        for (let r = 0; r < ROWS - 3; r++) {
            if (
                board.value[r][c] == color &&
                board.value[r + 1][c + 1] == color &&
                board.value[r + 2][c + 2] == color &&
                board.value[r + 3][c + 3] == color
            ) {
                return true
            }
        }
    }
}

function takeTurn(column:number) {
    if (gameOver.value == true) {
        return;
    }

    if (isColumnFull(column)) {
        return;
    }

    let row = findEmptyRowInColumn(column);
    let color = turn.value == 'player1' ? 1 : 2;

    board.value[row][column] = color;

    if (isWinningMove(color)) {
        gameOver.value = true;
    } else {
        turn.value = turn.value == 'player1' ? 'player2' : 'player1';
    }
}
</script>

<template>

    <GameLayout>
        <div class="flex flex-col items-center justify-center h-screen gap-4 rounded-xl p-4">
            <h1 class="font-extrabold text-xl" v-show="userName != ''">
                {{ userName }} requested a new game
            </h1>
            <h1 class="font-extrabold text-4xl" v-show="!gameOver">
                <span v-if="turn == 'player1'">Player 1</span><span v-else>{{ user }}</span>
            </h1>
            <h1 class="font-extrabold text-4xl" v-show="gameOver">
                <span v-if="turn == 'player1'">Player 1</span><span v-else>{{ user }}</span> Wins!
            </h1>
            <div class="flex justify-center pt-6">
                <div class="relative bg-blue-600 rounded-lg">
                    <div
                        v-for="(column, columnIndex) in board[0]"
                        :key="'col-' + columnIndex"
                        :style="{ left: columnIndex * 50 + 'px' }"
                        @click="takeTurn(columnIndex)"
                        :class="{
                            'rounded-l-lg': columnIndex == 0,
                            'rounded-r-lg': columnIndex == 6
                        }"
                        class="
                            hover:cursor-pointer
                            box-border
                            absolute
                            top-0
                            w-[50px]
                            h-full
                            bg-transparent
                            hover:bg-gray-50/20
                            transition-colors
                            duration-100
                            ease-in-out
                        ">
                            <div class="absolute -top-8 left-[21px]">{{ columnIndex + 1 }}</div>
                        </div>
                        <div v-for="(row, rowIndex) in board" :key="rowIndex" class="flex">
                            <svg
                                v-for="(column, columnIndex) in row"
                                :key="columnIndex"
                                width="50"
                                height="50"
                                :class="{
                                    'fill-white': board[rowIndex][columnIndex] == 0,
                                    'fill-red-600': board[rowIndex][columnIndex] == 1,
                                    'fill-yellow-400': board[rowIndex][columnIndex] == 2,
                                }"
                            >
                                <circle
                                    cx="25"
                                    cy="25"
                                    r="20"
                                    :id="'circle-' + rowIndex + '-' + columnIndex"
                                />
                            </svg>
                        </div>
                </div>
            </div>
        </div>
    </GameLayout>
</template>
