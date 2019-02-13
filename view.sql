create or replace view v_pembelian as
select distinct 'l' as tipe, id, dt, '' as ref_id,  name, '' as satuan, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan, deleted
    from beli_lain
    UNION
    select distinct 'o' as tipe, beli_obat.id, dt, obat_id as ref_id,  obat.name, obat.satuan, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan, beli_obat.deleted
    from beli_obat
    left join obat on obat.id = beli_obat.obat_id
    UNION
    select distinct 'p' as tipe, beli_pakan.id, dt, pakan_id as ref_id,  pakan.name, pakan.satuan, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan, beli_pakan.deleted
    from beli_pakan
    left join pakan on pakan.id = beli_pakan.pakan_id
;
-- ----------------------------
-- view untuk real stok pakan--
-- ---------------------------

create or replace view v_max_adj as
SELECT max(id) as id, pakan_id
from pakan_inventory_adj adj
group by pakan_id
;

create or replace view v_adj as
select adj.pakan_id, adj.write_time, adj.stok
from v_max_adj
left join pakan_inventory_adj adj on adj.id = v_max_adj.id
group by adj.pakan_id, adj.write_time
;

create or replace view v_beli_after_adj as
select beli.pakan_id, sum(beli.total_isi) as total_isi
from beli_pakan beli
left join v_max_adj on v_max_adj.pakan_id = beli.pakan_id
left join pakan_inventory_adj adj on adj.id = v_max_adj.id
where beli.dt >= adj.create_time
and beli.deleted = 0
group by beli.pakan_id
;

create or replace view v_tot_beli as
select pakan_id, sum(total_isi) as total
from beli_pakan
where deleted = 0
group by pakan_id
;

create or replace view v_mon as
select mon.pakan_id, sum(mon.jumlah_pakan) as total
from pembuatan_pakan mon
left join v_max_adj on v_max_adj.pakan_id = mon.pakan_id
left join pakan_inventory_adj adj on adj.id = v_max_adj.id
where mon.write_time >= adj.create_time
and mon.deleted = 0
group by mon.pakan_id
;


create or replace view v_tot_mon as
select pakan_id, sum(jumlah_pakan) as total
from pembuatan_pakan
where deleted = 0
group by pakan_id
;

create or replace view v_pakan_real_stok as
SELECT pakan.id as pakan_id, adj.stok as stok_adj, beli_after_adj.total_isi as total_pembelian_after_adj, tot_beli.total as total_pembelian_pakan
, mon.total as monitoring_after_adj, tot_mon.total as total_monitoring_pakan,
ifnull(adj.stok,0) + (case when adj.pakan_id is null then ifnull(tot_beli.total,0) else ifnull(beli_after_adj.total_isi,0) end) - (case when adj.pakan_id is null then ifnull(tot_mon.total,0) else ifnull(mon.total,0) end) as final_stok
from pakan
left join v_adj adj on adj.pakan_id = pakan.id
left join v_beli_after_adj beli_after_adj on beli_after_adj.pakan_id = pakan.id
left join v_tot_beli tot_beli on tot_beli.pakan_id = pakan.id
left join v_mon mon on mon.pakan_id = pakan.id
left join v_tot_mon tot_mon on tot_mon.pakan_id = pakan.id
;
-- ----------------------------
-- view untuk real stok obat--
-- ----------------------------

create or replace view v_max_adj_obat as
SELECT max(id) as id, obat_id
from obat_inventory_adj adj
group by obat_id
;

create or replace view v_adj_obat as
select adj.obat_id, adj.write_time, adj.stok
from v_max_adj_obat
left join obat_inventory_adj adj on adj.id = v_max_adj_obat.id
group by adj.obat_id, adj.write_time
;

create or replace view v_beli_after_adj_obat as
select beli.obat_id, sum(beli.total_isi) as total_isi
from beli_obat beli
left join v_max_adj_obat max_adj on max_adj.obat_id = beli.obat_id
left join obat_inventory_adj adj on adj.id = max_adj.id
where beli.dt >= adj.create_time
and beli.deleted = 0
group by beli.obat_id
;

create or replace view v_tot_beli_obat as
select obat_id, sum(total_isi) as total
from beli_obat
where deleted = 0
group by obat_id
;

create or replace view v_mon_obat as
select bahan.obat_id, sum(bahan.jumlah) as total
from bahan_penolong bahan
left join v_max_adj_obat on v_max_adj_obat.obat_id = bahan.obat_id
left join obat_inventory_adj adj on adj.id = v_max_adj_obat.id
where bahan.write_time >= adj.create_time
and bahan.deleted = 0
group by bahan.obat_id
;

create or replace view v_mon_sayur as
select bahan.obat_id, sum(bahan.jumlah) as total
from treatment_sayur bahan
left join v_max_adj_obat on v_max_adj_obat.obat_id = bahan.obat_id
left join obat_inventory_adj adj on adj.id = v_max_adj_obat.id
where bahan.write_time >= adj.create_time
and bahan.deleted = 0
group by bahan.obat_id
;

create or replace view v_pakan_obat as
select bahan.obat_id, sum(bahan.jumlah) as total
from pakan_obat bahan
left join v_max_adj_obat on v_max_adj_obat.obat_id = bahan.obat_id
left join obat_inventory_adj adj on adj.id = v_max_adj_obat.id
where bahan.write_time >= adj.create_time
and bahan.deleted = 0
group by bahan.obat_id
;

create or replace view v_tot_mon_obat as
select obat_id, sum(jumlah) as total
from bahan_penolong
where deleted = 0
group by obat_id
UNION
select obat_id, sum(jumlah) as total
from treatment_sayur
where deleted = 0
group by obat_id
UNION
select obat_id, sum(jumlah) as total
from pakan_obat
where deleted = 0
group by obat_id
;

create or replace view v_tot_mon_obat_group AS
select obat_id, sum(total) as total
from v_tot_mon_obat
group by obat_id
;

create or replace view v_obat_real_stok as
SELECT obat.id as obat_id, adj_obat.stok as stok_adj, beli_after_adj_obat.total_isi as total_pembelian_after_adj, tot_beli_obat.total as total_pembelian_obat
, ifnull(mon_obat.total,0) + ifnull(mon_sayur.total,0) + ifnull(pakan_obat.total,0) as monitoring_after_adj, tot_mon_obat.total as total_monitoring_obat,
ifnull(adj_obat.stok,0) + (case when adj_obat.obat_id is null then ifnull(tot_beli_obat.total,0) else ifnull(beli_after_adj_obat.total_isi,0) end) - (case when adj_obat.obat_id is null then ifnull(tot_mon_obat.total,0) else ifnull(mon_obat.total,0) + ifnull(mon_sayur.total,0) + ifnull(pakan_obat.total,0) end) as final_stok
from obat
left join v_adj_obat adj_obat on adj_obat.obat_id = obat.id
left join v_beli_after_adj_obat beli_after_adj_obat on beli_after_adj_obat.obat_id = obat.id
left join v_tot_beli_obat tot_beli_obat on tot_beli_obat.obat_id = obat.id
left join v_mon_obat mon_obat on mon_obat.obat_id = obat.id
left join v_mon_sayur mon_sayur on mon_sayur.obat_id = obat.id
left join v_pakan_obat pakan_obat on pakan_obat.obat_id = obat.id
left join v_tot_mon_obat_group tot_mon_obat on tot_mon_obat.obat_id = obat.id
;
-- ----------------------------
-- view untuk inventory adjustment--
-- ----------------------------

create or replace view v_inv_adj as
select 'p' as type, adj.pakan_id as ref_id, adj.stok, adj.create_time, pakan.name, k.name as create_user
from pakan_inventory_adj adj
left join pakan on pakan.id = adj.pakan_id
left join karyawan k on k.id = adj.create_uid
union
select 'o' as type, adj.obat_id as ref_id, adj.stok, adj.create_time, obat.name, k.name as create_user
from obat_inventory_adj adj
left join obat on obat.id = adj.obat_id
left join karyawan k on k.id = adj.create_uid
;

-- ----------------------------
-- view untuk monitoring--
-- ----------------------------

create or replace view v_air_pagi as
select *
from monitoring_air
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu = 'PAGI' and deleted = 0
;

create or replace view v_air_sore as
select *
from monitoring_air
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu = 'SORE' and deleted = 0
;

create or replace view v_pakan_pagi AS
select *
from monitoring_pakan
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu_pakan = 'PAGI' and deleted = 0
;

create or replace view v_pakan_sore AS
select *
from monitoring_pakan
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu_pakan = 'SORE' and deleted = 0
;

create or replace view v_pakan_malam AS
select *
from monitoring_pakan
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu_pakan = 'MALAM' and deleted = 0
;

create or replace view v_monitoring_all as
SELECT kolam.id as kolam_id, tebar.kode, case when air_pagi.id is not null then 1 else 0 end as air_pagi,
case when air_sore.id is not null then 1 else 0 end as air_sore,
case when pakan_pagi.id is not null then 1 else 0 end as pakan_pagi,
case when pakan_sore.id is not null then 1 else 0 end as pakan_sore,
case when pakan_malam.id is not null then 1 else 0 end as pakan_malam,
kolam.pemberian_pakan_id,
case when sampling.id != 0 then sampling.fcr else grading.fcr end as fcr,
sampling.id as sampling_id,
grading.id as grading_id,
ppk.total_ikan,
ppk.sampling,
ppk.angka,
ppk.satuan,
ppk.biomass
from kolam
left join v_air_pagi air_pagi on air_pagi.kolam_id = kolam.id and air_pagi.tebar_id = kolam.tebar_id
left join v_air_sore air_sore on air_sore.kolam_id = kolam.id and air_sore.tebar_id = kolam.tebar_id
left join v_pakan_pagi pakan_pagi on pakan_pagi.kolam_id = kolam.id and pakan_pagi.tebar_id = kolam.tebar_id
left join v_pakan_sore pakan_sore on pakan_sore.kolam_id = kolam.id and pakan_sore.tebar_id = kolam.tebar_id
left join v_pakan_malam pakan_malam on pakan_malam.kolam_id = kolam.id and pakan_malam.tebar_id = kolam.tebar_id
left join tebar on tebar.id = kolam.tebar_id
left join pemberian_pakan ppk on ppk.id = kolam.pemberian_pakan_id
left join sampling on sampling.id = ppk.sampling_id
left join grading on grading.id = ppk.grading_id
where kolam.tebar_id != 0
;

-- untuk lihat laporan keuangan
create or replace view v_lap_keuangan as
select dt, keterangan, jumlah, harga, total, 1 as jenis
from jual
where jual.deleted = 0
UNION
select dt, name, jumlah_item, harga_per_item, total_harga, 0 as jenis
from beli_lain
where beli_lain.deleted = 0
UNION
select dt, obat.name, jumlah_item, harga_per_item, total_harga, 0 as jenis
from beli_obat
left join obat on obat.id = beli_obat.obat_id
where beli_obat.deleted = 0
UNION
select dt, pakan.name, jumlah_item, harga_per_item, total_harga, 0 as jenis
from beli_pakan
left join pakan on pakan.id = beli_pakan.pakan_id
where beli_pakan.deleted = 0
order by dt asc
;

-- untuk lihat laporan monitoring air
-- available monitoring air
create or replace view v_lap_mon_air_available AS
select distinct kolam_id, tebar_id, pemberian_pakan_id, DATE_FORMAT(write_time , '%Y-%m-%d') as write_time
from monitoring_air mon
where deleted = 0
;

-- rangkuman bahan penolong tiap monitoring air
create or replace view v_lap_mon_air_bahan_penolong AS
select bhn.id, bhn.monitoring_air_id, GROUP_CONCAT(CONCAT(obat.name , '(' , bhn.jumlah , ' ', bhn.satuan, ')')) as obat_summary
from bahan_penolong bhn
left join obat on obat.id = obat_id
where bhn.deleted = 0
group by monitoring_air_id
;

-- mengambil monitoring air pagi
create or replace view v_lap_mon_air_pagi AS
select mon.*, bhn.obat_summary
from monitoring_air as mon
left join v_lap_mon_air_bahan_penolong as bhn on bhn.monitoring_air_id = mon.id
where mon.waktu = 'PAGI' and deleted = 0
;

-- mengambil monitoring air sore
create or replace view v_lap_mon_air_sore AS
select mon.*, bhn.obat_summary
from monitoring_air as mon
left join v_lap_mon_air_bahan_penolong as bhn on bhn.monitoring_air_id = mon.id
where mon.waktu = 'SORE' and deleted = 0
;

-- view laporan monitoring air secara keseluruhan
create or replace view v_lap_mon_air AS
select mon.write_time, CONCAT(blok.name, kolam.name) as kolam_name, tebar.kode, air_pagi.tinggi_air as tinggi_air_pagi, air_pagi.ph as ph_pagi, air_pagi.kcr as kcr_pagi, air_pagi.warna as warna_pagi, air_pagi.suhu as suhu_pagi, air_sore.ph as ph_sore, air_sore.suhu as suhu_sore,
air_sore.kcr as kcr_sore, air_sore.warna as warna_sore, air_sore.tinggi_air as tinggi_air_sore, CONCAT('PAGI: ', case when air_pagi.keterangan is not null then air_pagi.keterangan else '-' end, ', SORE: ', case when air_sore.keterangan is not null then air_sore.keterangan else '-' end) as keterangan,
CONCAT('PAGI: ', case when air_pagi.obat_summary is not null then air_pagi.obat_summary else '-' end, ', SORE: ', case when air_sore.obat_summary is not null then air_sore.obat_summary else '-' end) as obat_summary,
CONCAT('PAGI: ', case when air_pagi.warna is not null then air_pagi.warna else '-' end, ', SORE: ', case when air_sore.warna is not null then air_sore.warna else '-' end) as warna_summary,
air_pagi.obat_summary as pagi_obat_summary, air_sore.obat_summary as sore_obat_summary, air_pagi.keterangan as pagi_keterangan, air_sore.keterangan as sore_keterangan
from v_lap_mon_air_available mon
left join v_lap_mon_air_pagi as air_pagi on air_pagi.kolam_id = mon.kolam_id and air_pagi.tebar_id = mon.tebar_id and air_pagi.pemberian_pakan_id = mon.pemberian_pakan_id and DATE_FORMAT(air_pagi.write_time , '%Y-%m-%d') = DATE_FORMAT(mon.write_time , '%Y-%m-%d')
left join v_lap_mon_air_sore as air_sore on air_sore.kolam_id = mon.kolam_id and air_sore.tebar_id = mon.tebar_id and air_sore.pemberian_pakan_id = mon.pemberian_pakan_id and DATE_FORMAT(air_sore.write_time , '%Y-%m-%d') = DATE_FORMAT(mon.write_time , '%Y-%m-%d')
left join tebar on tebar.id = mon.tebar_id
left join kolam on kolam.id = mon.kolam_id
left join blok on blok.id = kolam.blok_id
;

-- untuk laporan monitoring pakan
-- mencari yang ada data monitoring pakannya
create or replace view v_lap_mon_pakan_available AS
select distinct kolam_id, tebar_id, pemberian_pakan_id, DATE_FORMAT(write_time , '%Y-%m-%d') as write_time
from monitoring_pakan mon
where deleted = 0
;

-- rangkuman bahan penolong tiap monitoring pakan
create or replace view v_lap_mon_pakan_bahan_penolong AS
select bhn.id, bhn.monitoring_pakan_id, GROUP_CONCAT(CONCAT(obat.name , '(' , bhn.jumlah , ' ', bhn.satuan, ')')) as obat_summary
from pakan_obat bhn
left join obat on obat.id = obat_id
where bhn.deleted = 0
group by monitoring_pakan_id
;

-- mengambil monitoring pakan pagi
create or replace view v_lap_mon_pakan_pagi AS
select mon.*, bhn.obat_summary
from monitoring_pakan as mon
left join v_lap_mon_pakan_bahan_penolong as bhn on bhn.monitoring_pakan_id = mon.id
where mon.waktu_pakan = 'PAGI' and deleted = 0
;

-- mengambil monitoring pakan pagi
create or replace view v_lap_mon_pakan_sore AS
select mon.*, bhn.obat_summary
from monitoring_pakan as mon
left join v_lap_mon_pakan_bahan_penolong as bhn on bhn.monitoring_pakan_id = mon.id
where mon.waktu_pakan = 'SORE' and deleted = 0
;

-- mengambil monitoring pakan malam
create or replace view v_lap_mon_pakan_malam AS
select mon.*, bhn.obat_summary
from monitoring_pakan as mon
left join v_lap_mon_pakan_bahan_penolong as bhn on bhn.monitoring_pakan_id = mon.id
where mon.waktu_pakan = 'MALAM' and deleted = 0
;

-- monitoring pakan
create or replace view v_lap_mon_pakan AS
SELECT mon.write_time, CONCAT(blok.name, kolam.name) as kolam_name, tebar.tgl_tebar, tebar.kode, round(pakan.sampling,2) as sampling, round(pakan.size,2) as size, round(pakan.biomass, 2) as biomass,
(case when pagi.kolam_id is not null then 'OK' else '-' end) as pakan_pagi, (case when sore.kolam_id is not null then 'OK' else '-' end) as pakan_sore, (case when malam.kolam_id is not null then 'OK' else '-' end) as pakan_malam,
case when pagi.id is not null and sore.id is not null and malam.id is not null then 100 else (case when pagi.id is not null then 33.33 else 0 end + case when sore.id is not null then 33.33 else 0 end + case when malam.id is not null then 33.33 else 0 end) end as persen_pakan,
round(case when pagi.jumlah_pakan is not null then pagi.jumlah_pakan else 0 end + case when sore.jumlah_pakan is not null then sore.jumlah_pakan else 0 end + case when malam.jumlah_pakan is not null then malam.jumlah_pakan else 0 end,2)  as total_pakan,
IFNULL(pagi.mr,0) + IFNULL(sore.mr,0) + IFNULL(malam.mr,0) as mr, CONCAT('PAGI: ', case when pagi.keterangan is not null then pagi.keterangan else '-' end , ', SORE: ', case when sore.keterangan is not null then sore.keterangan else '-' end , ', MALAM: ', case when malam.keterangan is not null then malam.keterangan else '-' end) as keterangan,
case when pagi.id is not null then p_pagi.name else (case when sore.id is not null then p_sore.name else p_malam.name end) end as jenis_pakan,
case when pagi.id is not null then pagi.obat_summary else (case when sore.id is not null then sore.obat_summary else malam.obat_summary end) end as summary_obat,
pagi.keterangan as ket_pagi, sore.keterangan as ket_sore, malam.keterangan as ket_malam
from v_lap_mon_pakan_available mon
left join kolam on mon.kolam_id = kolam.id
left join blok on blok.id = kolam.blok_id
left join tebar on tebar.id = mon.tebar_id
left join pemberian_pakan pakan on pakan.id = mon.pemberian_pakan_id
left join v_lap_mon_pakan_pagi pagi on pagi.kolam_id = mon.kolam_id and pagi.tebar_id = mon.tebar_id and pagi.pemberian_pakan_id = mon.pemberian_pakan_id and DATE_FORMAT(pagi.write_time , '%Y-%m-%d') = DATE_FORMAT(mon.write_time , '%Y-%m-%d')
left join v_lap_mon_pakan_sore sore on sore.kolam_id = mon.kolam_id and sore.tebar_id = mon.tebar_id and sore.pemberian_pakan_id = mon.pemberian_pakan_id and DATE_FORMAT(sore.write_time , '%Y-%m-%d') = DATE_FORMAT(mon.write_time , '%Y-%m-%d')
left join v_lap_mon_pakan_malam malam on malam.kolam_id = mon.kolam_id and malam.tebar_id = mon.tebar_id and malam.pemberian_pakan_id = mon.pemberian_pakan_id and DATE_FORMAT(malam.write_time , '%Y-%m-%d') = DATE_FORMAT(mon.write_time , '%Y-%m-%d')
left join pakan p_pagi on p_pagi.id = pagi.pakan_id
left join pakan p_sore on p_sore.id = sore.pakan_id
left join pakan p_malam on p_malam.id = malam.pakan_id
;

-- monitoring sayur
-- mencari yang ada data monitoring sayur
create or replace view v_lap_mon_sayur_available AS
select distinct DATE_FORMAT(write_time , '%Y-%m-%d') as write_time
from monitoring_sayur mon
where deleted = 0
;

-- summary treatment sayur
create or replace view v_lap_mon_treatment_sayur AS
select bhn.id, bhn.monitoring_sayur_id, GROUP_CONCAT(CONCAT(obat.name , '(' , bhn.jumlah , ' ', bhn.satuan, ')')) as obat_summary
from treatment_sayur bhn
left join obat on obat.id = obat_id
where bhn.deleted = 0
group by monitoring_sayur_id
;

-- mengambil monitoring sayur pagi
create or replace view v_lap_mon_sayur_pagi AS
select mon.*, bhn.obat_summary
from monitoring_sayur as mon
left join v_lap_mon_treatment_sayur as bhn on bhn.monitoring_sayur_id = mon.id
where mon.waktu = 'PAGI' and deleted = 0
;

-- mengambil monitoring sayur sore
create or replace view v_lap_mon_sayur_sore AS
select mon.*, bhn.obat_summary
from monitoring_sayur as mon
left join v_lap_mon_treatment_sayur as bhn on bhn.monitoring_sayur_id = mon.id
where mon.waktu = 'SORE' and deleted = 0
;

-- monitoring sayur
create or replace view v_lap_mon_sayur AS
select mon.write_time, pagi.ph as ph_pagi, pagi.tds as tds_pagi, sore.ph as ph_sore, sore.tds as tds_sore,
CONCAT('PAGI: ', case when pagi.keterangan is not null then pagi.keterangan else '-' end, ', SORE: ', case when sore.keterangan is not null then sore.keterangan else '-' end) as keterangan,
pagi.keterangan as ket_pagi, sore.keterangan as ket_sore, (pagi.obat_summary) as obat_pagi, (sore.obat_summary) as obat_sore
from v_lap_mon_sayur_available mon
left join v_lap_mon_sayur_pagi pagi on DATE_FORMAT(pagi.write_time , '%Y-%m-%d') = DATE_FORMAT(mon.write_time , '%Y-%m-%d')
left join v_lap_mon_sayur_sore sore on DATE_FORMAT(sore.write_time , '%Y-%m-%d') = DATE_FORMAT(mon.write_time , '%Y-%m-%d')
;

-- monitoring all parameter tanggal
select k.id, k.name as kolam_name, b.name as blok_name, tebar.kode, pkn.total_ikan,
(case when sampling.id is not null then sampling.fcr else grading.fcr end) as fcr,
(case when pagi.id is null then 0 else 1 end) as pakan_pagi,
(case when sore.id is null then 0 else 1 end) as pakan_sore,
(case when malam.id is null then 0 else 1 end) as pakan_malam,
(case when pagi_air.id is null then 0 else 1 end) as air_pagi,
(case when sore_air.id is null then 0 else 1 end) as air_sore
FROM
(
  select kolam.id, DATE_FORMAT('2019-01-10' , '%Y-%m-%d') as waktu
  from kolam
  where kolam.tebar_id != 0
) kolam
left join kolam k on k.id = kolam.id
left join blok b on b.id = k.blok_id
left join pemberian_pakan pkn on pkn.id = k.pemberian_pakan_id
left join tebar on tebar.id = k.tebar_id
left join sampling on pkn.sampling_id = sampling.id
left join grading on grading.id = pkn.grading_id
left JOIN
(
  select * from monitoring_pakan where waktu_pakan = 'PAGI' and DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('2019-01-10' , '%Y-%m-%d') and deleted = 0
) pagi on pagi.kolam_id = kolam.id
left JOIN
(
  select * from monitoring_pakan where waktu_pakan = 'SORE' and DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('2019-01-10' , '%Y-%m-%d') and deleted = 0
) sore on sore.kolam_id = kolam.id
left JOIN
(
  select * from monitoring_pakan where waktu_pakan = 'MALAM' and DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('2019-01-10' , '%Y-%m-%d') and deleted = 0
) malam on malam.kolam_id = kolam.id
left JOIN (
  select * from monitoring_air where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('2019-01-10' , '%Y-%m-%d') and waktu = 'PAGI' and deleted = 0
) pagi_air on pagi_air.kolam_id = kolam.id
left JOIN (
  select * from monitoring_air where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('2019-01-10' , '%Y-%m-%d') and waktu = 'SORE' and deleted = 0
) sore_air on sore_air.kolam_id = kolam.id
;

-- mengecek grading tersebut ada yang gabung kolam lain atau tidak
create or replace view v_grading_combine as
select DISTINCT th.grading_id
from tebar_history th
where keterangan = 'Grading Gabung Kolam' and deleted = 0
;

create or replace view v_sampling_combine as
select DISTINCT th.sampling_id
from tebar_history th
where keterangan like '%Sampling Setelah di Gabung%' and deleted = 0
;