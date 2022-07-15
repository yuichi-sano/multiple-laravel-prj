import {processModule} from '@/stores/process/Process';

export async function progress(func: () => Promise<void>) {
  if (processModule.isProcessing) {
    // return;
  }
  const idx = processModule.count;
  await processModule.add();
  await processModule.start(idx);

  try {
    await func();
  } finally {
      await processModule.end(idx);
  }
}
