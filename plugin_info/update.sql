IF NOT EXISTS (
  SELECT
    *
  FROM information_schema.columns
  WHERE
    table_name = 'onewire'
    AND column_name = 'class2'
) BEGIN
ALTER TABLE onewire
ADD
  COLUMN `class2` LONGTEXT NULL;
END;
UPDATE onewire
SET
  `class2` = '[address|alias|crc8|errata|family|fasttemp|id|locator|power|r_address|r_id|r_locator|scratchpad|temperature|temperature10|temperature11|temperature12|temperature9|temphigh|templow|type]'
WHERE
  `id` = '36';