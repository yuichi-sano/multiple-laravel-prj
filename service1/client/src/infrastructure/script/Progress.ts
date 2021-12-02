import {processModule} from '@/stores/process/Process';

export async function progress(func: () => Promise<void>) {
  if (processModule.isProcessing) {
    return;
  }
  await processModule.start();
  try {
    await func();
  } finally {
    await processModule.end();
  }
}
